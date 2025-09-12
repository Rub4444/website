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
//     public function callback(Request $request)
// {
//     \Log::info('📩 Telcell CALLBACK: получен запрос', [
//     'method'  => $request->method(),
//     'headers' => $request->headers->all(),
//     'payload' => $request->all(),
// ]);

//     $data = $request->all();

//     \Log::info('Telcell callback received', $data);

//     $issuerId  = $data['issuer_id'] ?? null;
//     $invoiceId = $data['invoice'] ?? null;
//     $status    = $data['status'] ?? null;

//     \Log::info('Parsed callback data', [
//         'issuerId' => $issuerId,
//         'invoiceId' => $invoiceId,
//         'status' => $status
//     ]);

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

//     $calculatedChecksum = md5($checksumString);
//     \Log::info('Checksum verification', [
//         'calculated' => $calculatedChecksum,
//         'received' => $data['checksum'] ?? null
//     ]);

//     if ($calculatedChecksum !== ($data['checksum'] ?? '')) {
//         \Log::error('Telcell checksum mismatch', [
//             'calculated' => $calculatedChecksum,
//             'received' => $data['checksum'] ?? null,
//         ]);
//         return response('Invalid checksum', 400);
//     }

//     $orderId = base64_decode($issuerId);
//     $order   = Order::find($orderId);
//     \Log::info('000000000', ['orderId' => $order->id, 'order' => $order]);

//     // if ($order)
//     // {
//     //     // $order->status = Order::STATUS_PAID;
//     //     $order->status = 2;
//     //     $order->invoice_status = $data['status'] ?? null;
//     //     $order->save();

//     //     \Log::info('Status updated successfully', ['orderId' => $order->id, 'status' => $order->status]);
//     // }

//     // Обновление статуса
//     if (strtoupper($status) === 'PAID') {
//         $order->markAsPaid();
//     } else {
//         $order->markAsCancelled();
//     }

//     // \Log::info('After updating status', ['orderId' => $order->id, 'newStatus' => $order->status]);

//     return response('OK', 200);
// }

public function callback(Request $request)
{
    // 1. Логируем входящие данные
    \Log::info('Telcell callback received', $request->all());

    // 2. Отвечаем Telcell максимально быстро
    response('OK', 200)->send();

    // 3. Выполняем остальную логику после ответа
    $this->processPayment($request);

    // 4. Завершаем выполнение, чтобы ничего лишнего не выполнялось
    exit;
}

protected function processPayment(Request $request)
{
    try {
        // Приводим ID к нормальному виду (base64 -> int)
        $invoiceId = $request->input('invoice');
        $issuerId  = $request->input('issuer_id');

        if ($issuerId) {
            $decodedIssuerId = base64_decode($issuerId);
        } else {
            $decodedIssuerId = null;
        }

        \Log::info('Processing payment', [
            'invoice' => $invoiceId,
            'issuer_id' => $issuerId,
            'decoded_issuer_id' => $decodedIssuerId
        ]);

        // Ищем заказ по invoice_id или issuer_id
        $order = Order::where('invoice_id', $invoiceId)
            ->orWhere('issuer_id', $decodedIssuerId)
            ->first();

        if (!$order) {
            \Log::warning('Order not found after callback', [
                'invoice' => $invoiceId,
                'issuer_id' => $decodedIssuerId
            ]);
            return;
        }

        // Проверяем статус из запроса
        if ($request->input('status') === 'success') {
            // $order->status = 'paid'; // или твой статус "Оплачен"
            // $order->save();
            $order->markAsPaid();

            \Log::info('Order marked as paid', [
                'order_id' => $order->id,
                'status' => $order->status
            ]);
        } else {
            \Log::warning('Callback status is not success', [
                'status' => $request->input('status')
            ]);
        }
    } catch (\Throwable $e) {
        \Log::error('Error in processing Telcell callback', [
            'message' => $e->getMessage(),
            'trace'   => $e->getTraceAsString(),
        ]);
    }
}


//    public function callback(Request $request)
//     {
//         // Логируем весь запрос
//         Log::info('📩 Telcell CALLBACK: получен запрос', [
//             'method'  => $request->method(),
//             'headers' => $request->headers->all(),
//             'payload' => $request->all(),
//         ]);

//         $data = $request->all();

//         $issuerId  = $data['issuer_id'] ?? null;
//         $invoiceId = $data['invoice'] ?? null;
//         $status    = $data['status'] ?? null;

//         Log::info('Parsed callback data', [
//             'issuerId' => $issuerId,
//             'invoiceId' => $invoiceId,
//             'status' => $status
//         ]);

//         // if (!$issuerId || !$invoiceId) {
//         //     Log::warning('Telcell callback missing issuer_id or invoice', $data);
//         //     return response('Invalid callback', 400);
//         // }

//         // Проверка checksum
//         $checksumString = config('services.telcell.shop_key')
//             . $invoiceId
//             . $issuerId
//             . ($data['payment_id'] ?? '')
//             . ($data['buyer'] ?? '')
//             . ($data['currency'] ?? '')
//             . ($data['sum'] ?? '')
//             . ($data['time'] ?? '')
//             . $status;

//         $calculatedChecksum = md5($checksumString);
//         Log::info('Checksum verification', [
//             'calculated' => $calculatedChecksum,
//             'received' => $data['checksum'] ?? null
//         ]);

//         if ($calculatedChecksum !== ($data['checksum'] ?? '')) {
//             Log::error('Telcell checksum mismatch', [
//                 'calculated' => $calculatedChecksum,
//                 'received' => $data['checksum'] ?? null,
//             ]);
//             return response('Invalid checksum', 400);
//         }

//         // Находим заказ по issuer_id
//         $order = Order::where('issuer_id', $issuerId)->first();

//         if (!$order) {
//             Log::warning('Order not found for issuer_id', ['issuer_id' => $issuerId]);
//             return response('Order not found', 404);
//         }

//         Log::info('Order found', ['orderId' => $order->id, 'currentStatus' => $order->status]);

//         $order->markAsPaid();
//         Log::info('Order marked as PAID', ['orderId' => $order->id]);
//         // Обновляем статус заказа
//         // if (strtoupper($status) === 'PAID') {
//         //     $order->markAsPaid();
//         //     Log::info('Order marked as PAID', ['orderId' => $order->id]);
//         // } else {
//         //     $order->markAsCancelled();
//         //     Log::info('Order marked as CANCELLED', ['orderId' => $order->id]);
//         // }

//         // Возвращаем успешный ответ Telcell
//         return response('OK', 200);
//     }

    /**
     * Возврат клиента после оплаты
     */
    public function handleReturn(Request $request)
{
    $orderId = $request->query('order');
    $status  = $request->query('status'); // получаем success/fail из URL

    if (!$orderId) {
        return redirect('/')->with('error', 'Պատվերի համարը նշված չէ');
    }

    $order = Order::find($orderId);
    if (!$order) {
        return redirect('/')->with('error', 'Պատվերը չի գտնվել');
    }

    // Можем дополнительно обновить статус в базе, если хотим
    if ($order->status != 2)
    {
        $order->status = 2;
        $order->save();
    }
    return view('payment.success', compact('order'));
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
            return redirect()->route('home')->with('warning', 'Վճարումը ընթացքի մեջ է');
        }

        return view('orders.success', compact('order'));
    }
}
