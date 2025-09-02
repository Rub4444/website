<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\TelcellService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // Создание счёта и редирект на оплату
    public function create(Order $order, TelcellService $telcell)
    {
        $buyer = $order->phone ?: $order->email;
        $response = $telcell->createInvoice(
            $buyer,
            $order->total,
            "Оплата заказа #{$order->id}",
            (string) $order->id
        );

        if (!$response || empty($response['invoice'])) {
            return back()->with('error', 'Ошибка при создании счёта.');
        }

        $invoiceId = $response['invoice'];

        return redirect()->away("https://telcellmoney.am/payments/invoice/?invoice={$invoiceId}&return_url=" . route('payment.return'));
    }

    // Callback от Telcell
    public function callback(Request $request, TelcellService $telcell)
    {
        $data = $request->all();

        if (!$telcell->verifyCallback($data)) {
            return response('Invalid checksum', 400);
        }

        $orderId = base64_decode($data['issuer_id']);
        $order = Order::find($orderId);

        if (!$order) {
            return response('Order not found', 404);
        }

        switch ($data['status']) {
            case 'PAID':
                $order->status = 2; // оплачено
                break;
            case 'REJECTED':
                $order->status = 3; // отменено
                break;
            case 'EXPIRED':
                $order->status = 4; // срок истек
                break;
        }


        $order->save();

        return response('OK', 200);
    }

    // Возврат клиента после оплаты
    public function return()
    {
        return view('payment.success');
    }
}



