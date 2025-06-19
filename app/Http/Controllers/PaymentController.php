<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function pay()
    {
        $orderId = rand(3954001, 3955000); // Тестовый OrderID
        $amount = 10; // 10 AMD

        $response = Http::post(env('AMERIA_INIT_URL'), [
            'ClientID' => env('AMERIA_CLIENT_ID'),
            'Username' => env('AMERIA_USERNAME'),
            'Password' => env('AMERIA_PASSWORD'),
            'OrderID' => $orderId,
            'Amount' => $amount,
            'Currency' => '051',
            'Description' => 'Test Payment Order #' . $orderId,
            'BackURL' => env('AMERIA_BACK_URL'),
        ]);

        $paymentId = $data['PaymentID'] ?? $data['MDOrderID'] ?? null;

        $data = $response->json();

        if ($data['ResponseCode'] == 1 || $data['ResponseCode'] == "00") {
            return redirect()->to(env('AMERIA_GATEWAY_URL') . "?id=" . $data['PaymentID'] . "&lang=am");
        } else {
            return "Ошибка инициализации оплаты: " . $data['ResponseMessage'];
        }
    }

    public function callback(Request $request)
    {
        $paymentId = $request->input('paymentID');

        $response = Http::post(env('AMERIA_DETAILS_URL'), [
            'PaymentID' => $paymentId,
            'Username' => env('AMERIA_USERNAME'),
            'Password' => env('AMERIA_PASSWORD'),
        ]);

        $data = $response->json();

        if ($data['ResponseCode'] === '00' && $data['PaymentState'] === 'payment_deposited') {
            return view('payment.success', compact('data'));
        } else {
            return view('payment.failed', compact('data'));
        }
    }

    public function cancel($paymentId)
{
    $response = Http::asForm()->post('https://servicestest.ameriabank.am/VPOS/api/VPOS/CancelPayment', [
        'PaymentID' => $paymentId,
        'Username' => env('AMERIA_USERNAME'),
        'Password' => env('AMERIA_PASSWORD'),
    ]);

    dd([
        'status' => $response->status(),
        'body' => $response->body(),
    ]);


    $data = $response->json();

    if ($data['ResponseCode'] === '00') {
        return "❌ Оплата успешно отменена. Message: " . $data['ResponseMessage'];
    }

    return "Ошибка отмены: " . $data['ResponseMessage'] ?? 'Неизвестная ошибка';
}


public function refund($paymentId)
{
    $response = Http::post('https://servicestest.ameriabank.am/VPOS/api/VPOS/RefundPayment', [
        'PaymentID' => $paymentId,
        'Username' => env('AMERIA_USERNAME'),
        'Password' => env('AMERIA_PASSWORD'),
        'Amount'   => 10, // возврат 10 AMD
    ]);

    $data = $response->json();

    if ($data['ResponseCode'] === '00') {
        return "💸 Возврат успешно выполнен. Message: " . $data['ResponseMessage'];
    }

    return "Ошибка возврата: " . $data['ResponseMessage'] ?? 'Неизвестная ошибка';
}
}
