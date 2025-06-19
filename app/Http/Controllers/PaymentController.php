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
public function cancel(string $paymentId)
    {
        // Նախ ստուգում ենք PaymentID-ի մանրամասները
        $details = $this->getPaymentDetails($paymentId);

        if (isset($details['ResponseCode']) && $details['ResponseCode'] === '00') {
            if (isset($details['PaymentStatus']) && $details['PaymentStatus'] === 'Completed') {
                // Վճարումը կարող է չեղարկվել, կատարում ենք չեղարկման հարցում
                return $this->sendCancelRequest($paymentId);
            } else {
                return "Վճարումը չի կարելի չեղարկել (չի համապատասխանում կարգավիճակը):";
            }
        } else {
            return "PaymentID գոյություն չունի կամ սխալ է։";
        }
    }
private function sendCancelRequest(string $paymentId)
    {
        $response = Http::post('https://servicestest.ameriabank.am/VPOS/api/VPOS/CancelPayment', [
            'PaymentID' => $paymentId,
            'Username' => env('AMERIA_USERNAME'),
            'Password' => env('AMERIA_PASSWORD'),
        ]);

        $data = $response->json();

        if (isset($data['ResponseCode']) && $data['ResponseCode'] === '00') {
            return "❌ Վճարումը հաջողությամբ չեղարկվեց։";
        }

        return "Չեղարկման սխալ: " . ($data['ResponseMessage'] ?? 'Չի հաջողվեց ստանալ մանրամասներ։');
    }
public function getPaymentDetails(string $paymentId)
{
    $response = Http::post('https://servicestest.ameriabank.am/VPOS/api/VPOS/GetPaymentDetails', [
        'PaymentID' => $paymentId,
        'Username' => env('AMERIA_USERNAME'),
        'Password' => env('AMERIA_PASSWORD'),
    ]);

    if ($response->failed()) {
        return ['error' => 'Սերվերի հետ խնդիր է: ' . $response->body()];
    }

    $data = $response->json();

    return $data;
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
