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
    $data = $request->all();

    // Логируем весь callback
    \Log::info('Telcell callback received', $data);

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

    $orderId = base64_decode($issuerId);
    \Log::info('Decoded order ID from issuer_id', ['orderId' => $orderId]);

    $order = Order::find($orderId);

    if (!$order) {
        \Log::error('Order not found by ID', ['orderId' => $orderId]);
        return response('Order not found', 404);
    }

    \Log::info('Order found, updating status', [
        'orderId' => $order->id,
        'currentStatus' => $order->status,
        'newStatus' => $status,
    ]);

    // Централизованное обновление статусов
    switch ($status) {
        case 'PAID':
            $order->markAsPaid();
            \Log::info('Order marked as PAID', ['orderId' => $order->id]);
            break;

        case 'REJECTED':
        case 'EXPIRED':
            $order->markAsCancelled();
            \Log::info('Order marked as CANCELLED', ['orderId' => $order->id]);
            break;

        default:
            \Log::warning('Unknown status received from Telcell', [
                'orderId' => $order->id,
                'status' => $status
            ]);
            break;
    }

    return response('OK', 200);
}
public function check(Request $request)
{
    if ($request->has('order')) {
        $order = Order::findOrFail($request->order);
        // Можно тут сделать проверку в Telcell API, чтобы подтвердить оплату
        return redirect()->route('payment.success', ['order' => $order->id]);
    }

    abort(404);
}

    /**
     * Возврат клиента после оплаты
     */
    // public function return(Request $request)
    // {
    //     return redirect()->route('home', $order->id)
    //                     ->with('success', 'Оплата прошла успешно!');
    // }
    public function return(Order $order)
{
    // Можно проверить статус заказа, чтобы показать правильное сообщение
    if ($order->status === Order::STATUS_PAID) {
        return redirect()->route('auth.orders.show', $order)
                         ->with('success', 'Оплата прошла успешно!');
    }

    return redirect()->route('auth.orders.show', $order)
                     ->with('error', 'Оплата не была завершена.');
}

}
