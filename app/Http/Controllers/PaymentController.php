<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function pay()
    {
        $orderId = rand(3954001, 3955000);
        $amount = 10;

        $response = Http::post(env('AMERIA_INIT_URL'), [
            'ClientID'   => env('AMERIA_CLIENT_ID'),
            'Username'   => env('AMERIA_USERNAME'),
            'Password'   => env('AMERIA_PASSWORD'),
            'OrderID'    => $orderId,
            'Amount'     => $amount,
            'Currency'   => '051',
            'Description'=> 'Test Payment Order #' . $orderId,
            'BackURL'    => env('AMERIA_BACK_URL'),
        ]);

        $data = $response->json();

        if (isset($data['ResponseCode']) && $data['ResponseCode'] === "00") {
            // Պահպանում ենք PaymentID session-ում հետագա օգտագործման համար
            session(['ameria_payment_id' => $data['PaymentID']]);

            return redirect()->to(env('AMERIA_GATEWAY_URL') . "?id=" . $data['PaymentID'] . "&lang=am");
        }

        return "❌ Սխալ ինիցիալիզացիայի ժամանակ: " . ($data['ResponseMessage'] ?? 'Չբացահայտված սխալ');
    }

    public function callback(Request $request)
    {
        $paymentId = $request->input('paymentID') ?? session('ameria_payment_id');

        if (!$paymentId) {
            return "❌ PaymentID չհայտնաբերվեց։";
        }

        $response = Http::post(env('AMERIA_DETAILS_URL'), [
            'PaymentID' => $paymentId,
            'Username'  => env('AMERIA_USERNAME'),
            'Password'  => env('AMERIA_PASSWORD'),
        ]);

        $data = $response->json();

        if (isset($data['ResponseCode']) && $data['ResponseCode'] === '00' &&
            isset($data['PaymentState']) && $data['PaymentState'] === 'payment_deposited') {
            return view('payment.success', compact('data'));
        }

        return view('payment.failed', compact('data'));
    }

    public function cancel(string $paymentId)
    {
        $details = $this->getPaymentDetails($paymentId);

        if (isset($details['ResponseCode']) && $details['ResponseCode'] === '00') {
            if (isset($details['PaymentState']) && $details['PaymentState'] === 'payment_deposited') {
                return $this->sendCancelRequest($paymentId);
            } else {
                return "❌ Վճարումը չի կարելի չեղարկել (կարգավիճակը՝ " . ($details['PaymentState'] ?? 'անհայտ') . ")";
            }
        }

        return "❌ PaymentID գոյություն չունի կամ սխալ է։";
    }

    private function getPaymentDetails(string $paymentId)
    {
        $response = Http::post(env('AMERIA_DETAILS_URL'), [
            'PaymentID' => $paymentId,
            'Username'  => env('AMERIA_USERNAME'),
            'Password'  => env('AMERIA_PASSWORD'),
        ]);

        if ($response->failed()) {
            return ['ResponseCode' => 'XX', 'ResponseMessage' => 'Սերվերի սխալ: ' . $response->body()];
        }

        return $response->json();
    }

    private function sendCancelRequest(string $paymentId)
    {
        $response = Http::post('https://servicestest.ameriabank.am/VPOS/api/VPOS/CancelPayment', [
            'PaymentID' => $paymentId,
            'Username'  => env('AMERIA_USERNAME'),
            'Password'  => env('AMERIA_PASSWORD'),
        ]);

        $data = $response->json();

        if (isset($data['ResponseCode']) && $data['ResponseCode'] === '00') {
            return "❌ Վճարումը հաջողությամբ չեղարկվեց։";
        }

        return "Չեղարկման սխալ: " . ($data['ResponseMessage'] ?? 'Չհաջողվեց ստանալ պատասխանը');
    }

    public function refund($paymentId)
    {
        $response = Http::post('https://servicestest.ameriabank.am/VPOS/api/VPOS/RefundPayment', [
            'PaymentID' => $paymentId,
            'Username'  => env('AMERIA_USERNAME'),
            'Password'  => env('AMERIA_PASSWORD'),
            'Amount'    => 10,
        ]);

        $data = $response->json();

        if (isset($data['ResponseCode']) && $data['ResponseCode'] === '00') {
            return "💸 Վերադարձը հաջողությամբ կատարվեց։ Message: " . $data['ResponseMessage'];
        }

        return "Ошибка возврата: " . ($data['ResponseMessage'] ?? 'Չհաջողվեց վերադարձը');
    }
}
