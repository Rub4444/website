<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\TelcellService;

class PaymentController extends Controller
{
    protected TelcellService $telcell;

    public function __construct(TelcellService $telcell)
    {
        $this->telcell = $telcell;
    }

    /**
     * Создание счёта и редирект на Telcell
     */
    public function createPayment(Order $order)
    {
        $buyer = $order->phone ?: $order->email;

        $response = $this->telcell->createInvoice(
            $buyer,
            $order->sum, // ✅ лучше использовать $order->sum из модели
            "Оплата заказа #{$order->id}",
            (string) $order->id,
            1
        );

        \Log::info('Telcell createInvoice response', $response);

        if (!isset($response['invoice'])) {
            return back()->withErrors(['Ошибка создания счёта']);
        }

        $invoiceId = $response['invoice'];

        // Редирект клиента на оплату
        return redirect()->away(
            "https://telcellmoney.am/payments/invoice/?invoice={$invoiceId}&return_url=" . route('auth.orders.index')
        );
    }

    /**
     * Callback от Telcell
     */
    public function callback(Request $request)
    {
        $data = $request->all();

        \Log::info('Telcell callback', $data);

        $invoiceId = $data['invoice'] ?? null;
        $issuerId  = $data['issuer_id'] ?? null;
        $status    = $data['status'] ?? null;

        if (!$invoiceId || !$issuerId) {
            return response('Invalid callback', 400);
        }

        // Проверка checksum
        $checksumString = config('services.telcell.shop_key') .
            $invoiceId .
            $issuerId .
            ($data['payment_id'] ?? '') .
            ($data['buyer'] ?? '') .
            ($data['currency'] ?? '') .
            ($data['sum'] ?? '') .
            ($data['time'] ?? '') .
            $status;

        if (md5($checksumString) !== ($data['checksum'] ?? '')) {
            return response('Invalid checksum', 400);
        }

        $orderId = base64_decode($issuerId);
        $order = Order::find($orderId);

        if (!$order) {
            return response('Order not found', 404);
        }

        // Централизованное обновление статусов
        switch ($status)
        {
            case 'PAID':
                $order->markAsPaid();
                break;
            case 'REJECTED':
            case 'EXPIRED':
                $order->markAsCancelled();
                break;
        }

        return response('OK', 200);
    }

    /**
     * Возврат клиента после оплаты
     */
    public function return(Request $request)
    {
        return redirect()->route('home', $order->id)
                        ->with('success', 'Оплата прошла успешно!');
    }

}
