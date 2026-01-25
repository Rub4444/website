<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\TelcellService;
use Illuminate\Support\Facades\Log;

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
    // public function createPayment(Order $order)
    // {
    //     $buyer = $order->phone ?: $order->email;
    //     $sum   = $order->sum;
    //     Log::alert("createPayment");
    //     // Генерируем HTML форму Telcell
    //     $formHtml = $this->telcell->createInvoiceHtml($buyer, $sum, $order->id);

    //     return response()->view('telcell.autopost', ['formHtml' => $formHtml]);
    // }

    /**
     * Callback от Telcell
     */

    // public function callback(Request $request)
    // {
    //     \Log::info('1111Telcell callback received', $request->all());
    //     $data = $request->all();

    //     // Логируем всё входящее
    //     \Log::info('1111Telcell callback received', $data);

    //     $invoiceId = $data['invoice'] ?? null;
    //     $issuerId  = $data['issuer_id'] ?? null;
    //     $status    = $data['status'] ?? null;

    //     if (!$invoiceId || !$issuerId) {
    //         \Log::warning('Missing invoice_id or issuer_id in callback', $data);
    //         return response('Invalid callback', 400);
    //     }

    //     // Проверка checksum
    //     $checksumString = config('services.telcell.shop_key')
    //         . $invoiceId
    //         . $issuerId
    //         . ($data['payment_id'] ?? '')
    //         . ($data['buyer'] ?? '') //nor em avelacrel
    //         . ($data['currency'] ?? '')
    //         . ($data['sum'] ?? '')
    //         . ($data['time'] ?? '')
    //         . $status;

    //     $calculatedChecksum = md5($checksumString);

    //     if ($calculatedChecksum !== ($data['checksum'] ?? '')) {
    //         \Log::error('Checksum mismatch', ['calculated' => $calculatedChecksum, 'received' => $data['checksum'] ?? null]);
    //         return response('Invalid checksum', 400);
    //     }

    //     $orderId = base64_decode($issuerId);
    //     $order = Order::find($orderId);

    //     if (!$order) {
    //         \Log::warning('Order not found', ['order_id' => $orderId]);
    //         return response('Order not found', 404);
    //     }

    //     // Обновляем статус и логируем
    //     if ($status === 'PAID') {
    //         $order->markAsPaid();
    //         \Log::info('Order marked as PAID', ['order_id' => $order->id]);
    //     } elseif ($status === 'REJECTED') {
    //         $order->markAsCancelled();
    //         \Log::info('Order marked as REJECTED', ['order_id' => $order->id]);
    //     } else {
    //         \Log::warning('Unknown payment status', ['status' => $status, 'order_id' => $order->id]);
    //     }

    //     return response('OK', 200);
    // }


    // public function callback(Request $request)
    // {
    //     // 1. Логируем входящие данные
    //     \Log::info('Telcell callback received', $request->all());

    //     // 2. Отвечаем Telcell максимально быстро
    //     response('OK', 200)->send();

    //     // 3. Выполняем остальную логику после ответа
    //     $this->processPayment($request);

    //     // 4. Завершаем выполнение, чтобы ничего лишнего не выполнялось
    //     exit;
    // }

    public function callback(Request $request)
    {
        Log::info('Telcell callback', $request->all());

        $status   = strtoupper($request->input('status'));
        $issuerId = $request->input('issuer_id');

        if (!$issuerId) {
            return response('OK', 200);
        }

        // твой формат: base64(order_id|timestamp)
        if (str_contains($issuerId, '|')) {
            // пришёл как "152|timestamp"
            [$orderId] = explode('|', $issuerId);
        } else {
            // пришёл в base64
            [$orderId] = explode('|', base64_decode($issuerId));
        }
        $orderId = (int) $orderId;

        $order = Order::find($orderId);
        if (!$order) {
            return response('OK', 200);
        }

        // idempotency
        if ($order->status === Order::STATUS_PAID) {
            return response('OK', 200);
        }
        // if ($status === 'PAID') {
        //     $order->update([
        //         'status' => Order::STATUS_PAID,
        //         'invoice_status' => 'PAID'
        //     ]);
        // }

        if ($status === 'PAID') {
            $order->markAsPaid();
            $order->update(['invoice_status' => 'PAID']);
        }

        if ($status === 'REJECTED') {
            $order->markAsCancelled();
            $order->update(['invoice_status' => 'REJECTED']);
        }

        return response('OK', 200);
    }



    // protected function processPayment(Request $request)
    // {
    //     try {
    //         $invoiceId = $request->input('invoice');
    //         $issuerId  = $request->input('issuer_id');
    //         $status    = strtoupper($request->input('status', ''));

    //         $decodedIssuerId = $issuerId ? base64_decode($issuerId) : null;

    //         $order = Order::where('invoice_id', $invoiceId)
    //             ->orWhere('issuer_id', $decodedIssuerId)
    //             ->first();

    //         if (!$order)
    //         {
    //             \Log::warning('Order not found after callback', [
    //                 'invoice' => $invoiceId,
    //                 'issuer_id' => $decodedIssuerId
    //             ]);
    //             return;
    //         }

    //         if ($status === 'PAID')
    //         {
    //             $order->markAsPaid();
    //             // Отправка письма
    //             Mail::to($email)->send(new OrderCreated($name, $order));
    //             // \Log::info('Order marked as PAID', ['order_id' => $order->id]);
    //         }
    //         elseif ($status === 'REJECTED')
    //         {
    //             $order->markAsCancelled();
    //             // \Log::info('Order marked as REJECTED', ['order_id' => $order->id]);
    //         }
    //         else
    //         {
    //             \Log::warning('Unknown payment status', ['status' => $status, 'order_id' => $order->id]);
    //         }

    //     }
    //     catch (\Throwable $e)
    //     {
    //         \Log::error('Error in processing Telcell callback', [
    //             'message' => $e->getMessage(),
    //             'trace'   => $e->getTraceAsString(),
    //         ]);
    //     }
    // }



    /**
     * Возврат клиента после оплаты
     */
    public function handleReturn(Request $request)
{
    Log::alert('handleReturn');

    $orderRaw = $request->input('order');
    $orderId = (int) explode('|', $orderRaw)[0];
    $order = Order::find($orderId);

    if (!$order) {
        return redirect('/')->with('error', 'Պատվերը չի գտնվել');
    }

    if ($order->status === Order::STATUS_PAID) {
        return view('payment.success', compact('order'));
    }
    // if ($order->invoice_status === 'PAID') {
    //     return redirect()->route('payment.success', $order);
    // }


    if ($order->invoice_status === 'REJECTED') {
        return view('payment.fail', compact('order'));
    }

    return view('payment.pending', compact('order'));
}


    public function pending(Order $order)
    {
        return view('payment.pending', compact('order'));
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
