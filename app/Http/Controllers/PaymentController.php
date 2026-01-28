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

    public function callback(Request $request)
    {
        Log::info('Telcell callback', $request->all());

        $status   = strtoupper($request->input('status'));
        $issuerId = $request->input('issuer_id');

        if (!$issuerId) {
            return response('OK', 200);
        }

        if (str_contains($issuerId, '|')) {
            [$orderId] = explode('|', $issuerId);
        } else {
            [$orderId] = explode('|', base64_decode($issuerId));
        }
        $orderId = (int) $orderId;

        $order = Order::find($orderId);
        if (!$order) {
            return response('OK', 200);
        }

        if ($order->status === Order::STATUS_PAID) {
            return response('OK', 200);
        }

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

        return redirect()->route('payment.pending', [
            'order_id' => $orderId
        ]);
    }

    public function pending(Request $request)
    {
        $orderId = (int) $request->get('order_id');

        $order = Order::find($orderId);

        if (!$order) {
            return redirect()->route('payment.failed');
        }

        if ($order->status === Order::STATUS_PAID) {
            return redirect()->route('payment.success');
        }

        if ($order->invoice_status === 'REJECTED') {
            return redirect()->route('payment.failed');
        }

        return view('payment.pending', compact('order'));
    }

    public function success()
    {
        return view('payment.success');
    }

    public function failed()
    {
        return view('payment.failed');
    }
}
