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
     * Создание платежа и редирект на страницу Telcell
     */
    public function create(Order $order)
    {
        // buyer = телефон (ean13) или email
        $buyer = $order->phone ?: $order->email;

        $result = $this->telcell->createInvoice(
            $buyer,
            $order->total,
            "Оплата заказа №{$order->id}",
            (string) $order->id
        );

        if (!$result || empty($result['invoice'])) {
            return back()->withErrors(['Ошибка создания платежа']);
        }

        $invoiceId = $result['invoice'];

        return redirect()->away(
            "https://telcellmoney.am/payments/invoice/?invoice={$invoiceId}&return_url=" . route('payment.return')
        );
    }

    /**
     * Callback от Telcell
     */
    public function callback(Request $request)
    {
        $data = $request->all();

        \Log::info('Telcell callback', $data);

        if (!$this->telcell->verifyCallback($data)) {
            return response('Invalid checksum', 400);
        }

        $orderId = base64_decode($data['issuer_id']); // наш order_id
        $order   = Order::find($orderId);

        if (!$order) {
            return response('Order not found', 404);
        }

        switch ($data['status']) {
            case 'PAID':
                $order->status = 'paid';
                break;
            case 'REJECTED':
                $order->status = 'failed';
                break;
            case 'EXPIRED':
                $order->status = 'expired';
                break;
            default:
                $order->status = 'pending';
        }

        $order->save();

        return response('OK', 200);
    }

    /**
     * Возврат клиента после оплаты
     */
    public function return()
    {
        return view('payment.success');
    }
}
