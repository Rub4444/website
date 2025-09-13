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
    public function createPayment(Order $order)
    {
        $buyer = $order->phone ?: $order->email;
        $sum   = $order->sum;
        Log::alert("createPayment");
        // Генерируем HTML форму Telcell
        $formHtml = $this->telcell->createInvoiceHtml($buyer, $sum, $order->id);

        return response()->view('telcell.autopost', ['formHtml' => $formHtml]);
    }

    /**
     * Callback от Telcell
     */

    public function callback(Request $request)
    {
        \Log::info('1111Telcell callback received', $request->all());
        $data = $request->all();

        // Логируем всё входящее
        \Log::info('1111Telcell callback received', $data);

        $invoiceId = $data['invoice'] ?? null;
        $issuerId  = $data['issuer_id'] ?? null;
        $status    = $data['status'] ?? null;

        if (!$invoiceId || !$issuerId) {
            \Log::warning('Missing invoice_id or issuer_id in callback', $data);
            return response('Invalid callback', 400);
        }

        // Проверка checksum
        $checksumString = config('services.telcell.shop_key')
            . $invoiceId
            . $issuerId
            . ($data['payment_id'] ?? '')
            . ($data['buyer'] ?? '') //nor em avelacrel
            . ($data['currency'] ?? '')
            . ($data['sum'] ?? '')
            . ($data['time'] ?? '')
            . $status;

        $calculatedChecksum = md5($checksumString);

        if ($calculatedChecksum !== ($data['checksum'] ?? '')) {
            \Log::error('Checksum mismatch', ['calculated' => $calculatedChecksum, 'received' => $data['checksum'] ?? null]);
            return response('Invalid checksum', 400);
        }

        $orderId = base64_decode($issuerId);
        $order = Order::find($orderId);

        if (!$order) {
            \Log::warning('Order not found', ['order_id' => $orderId]);
            return response('Order not found', 404);
        }

        // Обновляем статус и логируем
        if ($status === 'PAID') {
            $order->markAsPaid();
            \Log::info('Order marked as PAID', ['order_id' => $order->id]);
        } elseif ($status === 'REJECTED') {
            $order->markAsCancelled();
            \Log::info('Order marked as REJECTED', ['order_id' => $order->id]);
        } else {
            \Log::warning('Unknown payment status', ['status' => $status, 'order_id' => $order->id]);
        }

        return response('OK', 200);
    }


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
        Log::alert("handleReturn");

        $order = Order::find($request->order);

        if (!$order) {
            return redirect('/')->with('error', 'Պատվերի համարը նշված չէ');
        }
        if (!$order) {
            return redirect('/')->with('error', 'Պատվերը չի գտնվել');
        }

        if ($order->status === 'PAID')
        {
            return view('payment.success', compact('order'));
        }
        elseif ($order->status === 'REJECTED')
        {
            return view('payment.fail', compact('order'));
        }
        else
        {
            return redirect('/')->with('warning', 'Վճարումը դեռեւս չի հաստատվել');
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
            return redirect()->route('home')->with('warning', 'Վճարումը ընթացքի մեջ է');
        }

        return view('orders.success', compact('order'));
    }
}
