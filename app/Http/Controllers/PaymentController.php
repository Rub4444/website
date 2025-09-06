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
        $sum   = $order->sum;

        // Генерируем HTML форму Telcell
        $formHtml = $this->telcell->createInvoiceHtml($buyer, $sum, $order->id);

        return response()->view('telcell.autopost', ['formHtml' => $formHtml]);
    }

    /**
     * Callback от Telcell
     */
    // public function callback(Request $request)
    // {
    //     $data = $request->all();

    //     \Log::info('Telcell callback received', $data);

    //     $issuerId  = $data['issuer_id'] ?? null;
    //     $invoiceId = $data['invoice'] ?? null;
    //     $status    = $data['status'] ?? null;

    //     if (!$issuerId || !$invoiceId) {
    //         \Log::warning('Telcell callback missing issuer_id or invoice', $data);
    //         return response('Invalid callback', 400);
    //     }

    //     // Проверка checksum
    //     $checksumString = config('services.telcell.shop_key')
    //         . $invoiceId
    //         . $issuerId
    //         . ($data['payment_id'] ?? '')
    //         . ($data['buyer'] ?? '')
    //         . ($data['currency'] ?? '')
    //         . ($data['sum'] ?? '')
    //         . ($data['time'] ?? '')
    //         . $status;

    //     if (md5($checksumString) !== ($data['checksum'] ?? '')) {
    //         \Log::error('Telcell checksum mismatch', [
    //             'calculated' => md5($checksumString),
    //             'received'   => $data['checksum'] ?? null,
    //         ]);
    //         return response('Invalid checksum', 400);
    //     }

    //     $orderId = base64_decode($issuerId);
    //     $order   = Order::find($orderId);

    //     if (!$order) {
    //         \Log::warning('Telcell callback order not found', ['orderId' => $orderId]);
    //         return response('Order not found', 404);
    //     }

    //     // Обновление статуса
    //     if (strtoupper($status) === 'PAID')
    //     {
    //         $order->markAsPaid();
    //     }
    //     else
    //     {
    //         $order->markAsCancelled();
    //     }


    //     \Log::info('Telcell callback SUCCESS', [
    //         'orderId' => $order->id,
    //         'newStatus' => $order->status,
    //     ]);

    //     return response('OK', 200);
    // }

    public function callback(Request $request)
{
    \Log::info('📩 Telcell CALLBACK: получен запрос', [
    'method'  => $request->method(),
    'headers' => $request->headers->all(),
    'payload' => $request->all(),
]);

    $data = $request->all();

    \Log::info('Telcell callback received', $data);

    $issuerId  = $data['issuer_id'] ?? null;
    $invoiceId = $data['invoice'] ?? null;
    $status    = $data['status'] ?? null;

    \Log::info('Parsed callback data', [
        'issuerId' => $issuerId,
        'invoiceId' => $invoiceId,
        'status' => $status
    ]);

    if (!$issuerId || !$invoiceId) {
        \Log::warning('Telcell callback missing issuer_id or invoice', $data);
        return response('Invalid callback', 400);
    }

    // Проверка checksum
    $checksumString = config('services.telcell.shop_key')
        . $invoiceId
        . $issuerId
        . ($data['payment_id'] ?? '')
        . ($data['buyer'] ?? '')
        . ($data['currency'] ?? '')
        . ($data['sum'] ?? '')
        . ($data['time'] ?? '')
        . $status;

    $calculatedChecksum = md5($checksumString);
    \Log::info('Checksum verification', [
        'calculated' => $calculatedChecksum,
        'received' => $data['checksum'] ?? null
    ]);

    if ($calculatedChecksum !== ($data['checksum'] ?? '')) {
        \Log::error('Telcell checksum mismatch', [
            'calculated' => $calculatedChecksum,
            'received' => $data['checksum'] ?? null,
        ]);
        return response('Invalid checksum', 400);
    }

    $orderId = base64_decode($issuerId);
    $order   = Order::find($orderId);
    \Log::info('000000000', ['orderId' => $order->id, 'order' => $order]);

    // if ($order)
    // {
    //     // $order->status = Order::STATUS_PAID;
    //     $order->status = 2;
    //     $order->invoice_status = $data['status'] ?? null;
    //     $order->save();

    //     \Log::info('Status updated successfully', ['orderId' => $order->id, 'status' => $order->status]);
    // }

    // Обновление статуса
    if (strtoupper($status) === 'PAID') {
        $order->markAsPaid();
    } else {
        $order->markAsCancelled();
    }

    // \Log::info('After updating status', ['orderId' => $order->id, 'newStatus' => $order->status]);

    return response('OK', 200);
}
    // public function callback(Request $request)
    // {
    //     \Log::info('111111111', $request->all());
    //     return response('OK', 200);
    // }


    /**
     * Возврат клиента после оплаты
     */
    // public function handleReturn(Request $request)
    // {
    //     $orderId = $request->query('order');
    //     $order = Order::find($orderId);

    //     if (!$order)
    //     {
    //         abort(404, 'Order not found');
    //     }

    //     return view('payment.success', compact('order'));
    // }
    public function handleReturn(Request $request)
    {
        $orderId = $request->query('order'); // получаем ?order=40
        if (!$orderId) {
            return redirect('/')->with('error', 'Не указан номер заказа.');
        }

        $order = Order::find($orderId);
        if (!$order) {
            return redirect('/')->with('error', 'Заказ не найден.');
        }

        // Если статус оплаты в базе уже обновился после callback-а
        if ($order->status == 2)
        {
            return view('payment.success', compact('order'));
        }
        else
        {
            return view('payment.fail', compact('order'));
        }
    }
    /**
     * Страница успешного платежа
     */
    public function success(Order $order)
    {
        // if ($order->status !== 'paid') {
        //     return redirect()->route('home')->with('warning', 'Платёж обрабатывается.');
        // }

        if (!$order->isStatus(Order::STATUS_PAID))
        {
            return redirect()->route('home')->with('warning', 'Платёж обрабатывается.');
        }

        return view('orders.success', compact('order'));
    }
}
