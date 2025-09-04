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

        // \Log::info('Telcell createInvoice response', $response);

        if (!isset($response['invoice'])) {
            return back()->withErrors(['Ошибка создания счёта']);
        }

        $invoiceId = $response['invoice'];

        // Редирект клиента на оплату
        return redirect()->away(
            "https://telcellmoney.am/payments/invoice/?invoice={$invoiceId}&return_url="
            . route('payment.return', ['order' => $order->id])
        );

    }

    /**
     * Callback от Telcell
     */
    // public function callback(Request $request)
    // {
    //     $data = $request->all();

    //     \Log::info('Telcell callback', $data);

    //     $invoiceId = $data['invoice'] ?? null;
    //     $issuerId  = $data['issuer_id'] ?? null;
    //     $status    = $data['status'] ?? null;

    //     if (!$invoiceId || !$issuerId) {
    //         return response('Invalid callback', 400);
    //     }

    //     // Проверка checksum
    //     $checksumString = config('services.telcell.shop_key') .
    //         $invoiceId .
    //         $issuerId .
    //         ($data['payment_id'] ?? '') .
    //         ($data['buyer'] ?? '') .
    //         ($data['currency'] ?? '') .
    //         ($data['sum'] ?? '') .
    //         ($data['time'] ?? '') .
    //         $status;

    //     if (md5($checksumString) !== ($data['checksum'] ?? '')) {
    //         return response('Invalid checksum', 400);
    //     }

    //     $orderId = base64_decode($issuerId);
    //     $order = Order::find($orderId);

    //     if (!$order) {
    //         return response('Order not found', 404);
    //     }

    //     // Централизованное обновление статусов
    //     switch ($status)
    //     {
    //         case 'PAID':
    //             $order->markAsPaid();
    //             break;
    //         case 'REJECTED':
    //         case 'EXPIRED':
    //             $order->markAsCancelled();
    //             break;
    //     }

    //     return response('OK', 200);
    // }
public function callback(Request $request)
{
    \Log::info('Telcell callback START', [
        'headers' => $request->headers->all(),
        'payload' => $request->all(),
    ]);

    $data = $request->all();

    if (! isset($data['orderId'])) {
        \Log::warning('Telcell callback missing orderId', $data);
        return response('Missing orderId', 400);
    }

    $order = Order::find($data['orderId']);
    if (! $order) {
        \Log::warning('Telcell callback order not found', ['orderId' => $data['orderId']]);
        return response('Order not found', 404);
    }

    $invoiceId = $data['invoice'] ?? null;
    $issuerId  = $data['issuer_id'] ?? null;
    $status    = $data['status'] ?? null;

    if (!$invoiceId || !$issuerId) {
        \Log::warning('Telcell callback missing invoiceId or issuerId', [
            'invoiceId' => $invoiceId,
            'issuerId' => $issuerId,
        ]);
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

    $calculatedChecksum = md5($checksumString);

    if ($calculatedChecksum !== ($data['checksum'] ?? '')) {
        \Log::error('Telcell checksum mismatch', [
            'calculated' => $calculatedChecksum,
            'received'   => $data['checksum'] ?? null,
            'string'     => $checksumString,
        ]);
        return response('Invalid checksum', 400);
    }
    $order->markAsPaid();
    // $orderId = base64_decode($issuerId);
    // \Log::info('Decoded order ID from issuer_id', ['orderId' => $orderId]);

    // $order = Order::find($orderId);

    \Log::info('Telcell callback SUCCESS', [
            'orderId' => $order->id,
            'newStatus' => $order->status,
        ]);

    return response('OK', 200);
}

public function success(Order $order)
{
    if ($order->status !== 'paid') {
        // Тут можешь сделать дополнительную проверку на всякий случай
        return redirect()->route('home')->with('warning', 'Платёж обрабатывается.');
    }

    return view('orders.success', compact('order'));
}

    /**
     * Возврат клиента после оплаты
     */
    // public function return(Request $request)
    // {
    //     return redirect()->route('home', $order->id)
    //                     ->with('success', 'Оплата прошла успешно!');
    // }
public function handleReturn(Request $request)
{
    $orderId = $request->query('order'); // получаем из ?order=42
    $order = Order::find($orderId);

    if (! $order)
    {
        abort(404, 'Order not found');
    }
    // return response()->json(['ok' => true, 'order' => $order->id]);

    // тут твоя логика: например, показать страницу статуса заказа
    return view('payment.success', ['order' => $order]);
}



}
