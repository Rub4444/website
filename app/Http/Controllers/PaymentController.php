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
            'ClientID'    => env('AMERIA_CLIENT_ID'),
            'Username'    => env('AMERIA_USERNAME'),
            'Password'    => env('AMERIA_PASSWORD'),
            'OrderID'     => $orderId,
            'Amount'      => $amount,
            'Currency'    => '051',
            'Description' => 'Test Payment Order #' . $orderId,
            'BackURL'     => env('AMERIA_BACK_URL'),
        ]);

        if ($response->failed()) {
            return "❌ Սխալ InitPayment հարցման ժամանակ։\n" . $response->body();
        }

        $data = $response->json();
        $paymentId = $data['PaymentID'] ?? $data['MDOrderID'] ?? null;

        if (isset($data['ResponseCode']) && (in_array($data['ResponseCode'], ['00', 1, '1']))) {
            return redirect()->to(env('AMERIA_GATEWAY_URL') . "?id=" . $paymentId . "&lang=am");
        }

        return "❌ Սխալ ինիցիալիզացիայի ժամանակ: " . ($data['ResponseMessage'] ?? 'Անհայտ սխալ');
    }

    public function callback(Request $request)
    {
        $paymentId = $request->input('paymentID') ?? $request->get('paymentID');

        if (!$paymentId) {
            return "❌ PaymentID բացակայում է callback-ում։";
        }

        $data = $this->getPaymentDetails($paymentId);

        if (isset($data['ResponseCode']) && $data['ResponseCode'] === '00' && $data['PaymentState'] === 'payment_deposited') {
            return view('payment.success', compact('data'));
        }

        return view('payment.failed', compact('data'));
    }

    public function cancel(string $paymentId)
    {
        if (empty($paymentId)) {
            return "❌ PaymentID չի փոխանցվել։";
        }

        $details = $this->getPaymentDetails($paymentId);

        if (!isset($details['ResponseCode']) || $details['ResponseCode'] !== '00') {
            return "❌ PaymentID գոյություն չունի կամ սխալ է։";
        }

        if ($details['PaymentState'] === 'payment_deposited') {
            return "❌ Չի կարելի չեղարկել, քանի որ վճարումը արդեն կատարվել է։ Փորձիր կատարել վերադարձ (refund):";
        }

        return $this->sendCancelRequest($paymentId);
    }

    public function refund(string $paymentId)
    {
        if (empty($paymentId)) {
            return "❌ PaymentID չի փոխանցվել։";
        }

        $details = $this->getPaymentDetails($paymentId);

        if (!isset($details['ResponseCode']) || $details['ResponseCode'] !== '00') {
            return "❌ Սխալ `PaymentDetails` հարցման ժամանակ։";
        }

        if ($details['PaymentState'] !== 'payment_deposited') {
            return "❌ Չի կարելի կատարել վերադարձ։ Վճարումը դեռ չի կատարվել։";
        }

        $response = Http::post('https://servicestest.ameriabank.am/VPOS/api/VPOS/RefundPayment', [
            'PaymentID' => $paymentId,
            'Username'  => env('AMERIA_USERNAME'),
            'Password'  => env('AMERIA_PASSWORD'),
            'Amount'    => 10, // կարգաբերվող
        ]);

        if ($response->failed()) {
            return "❌ HTTP սխալ վերադարձի ժամանակ:\n" . $response->body();
        }

        $data = $response->json();

        if (isset($data['ResponseCode']) && $data['ResponseCode'] === '00') {
            return "💸 Վերադարձը հաջողությամբ կատարվեց։";
        }

        return "❌ Սխալ վերադարձի ժամանակ: " . ($data['ResponseMessage'] ?? 'Անհայտ սխալ');
    }

    private function sendCancelRequest(string $paymentId)
    {
        $response = Http::post('https://servicestest.ameriabank.am/VPOS/api/VPOS/CancelPayment', [
            'PaymentID' => $paymentId,
            'Username'  => env('AMERIA_USERNAME'),
            'Password'  => env('AMERIA_PASSWORD'),
        ]);

        if ($response->failed()) {
            return "❌ HTTP սխալ չեղարկման ժամանակ:\n" . $response->body();
        }

        $data = $response->json();

        if (isset($data['ResponseCode']) && $data['ResponseCode'] === '00') {
            return "❌ Վճարումը հաջողությամբ չեղարկվեց։";
        }

        return "Չեղարկման սխալ: " . ($data['ResponseMessage'] ?? 'Չի հաջողվել ստանալ մանրամասներ։');
    }

    public function getPaymentDetails(string $paymentId)
    {
        $response = Http::post('https://servicestest.ameriabank.am/VPOS/api/VPOS/GetPaymentDetails', [
            'PaymentID' => $paymentId,
            'Username'  => env('AMERIA_USERNAME'),
            'Password'  => env('AMERIA_PASSWORD'),
        ]);

        if ($response->failed()) {
            return [
                'ResponseCode' => '99',
                'ResponseMessage' => 'Սերվերի հետ խնդիր է։ ' . $response->body()
            ];
        }

        return $response->json();
    }

    public function cancelPost(Request $request)
    {
        $paymentId = $request->input('paymentId');
        return $this->cancel($paymentId);
    }

    public function refundPost(Request $request)
    {
        $paymentId = $request->input('paymentId');
        return $this->refund($paymentId);
    }

}
