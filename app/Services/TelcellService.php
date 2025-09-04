<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelcellService
{
    protected string $issuer; // идентификатор магазина
    protected string $key;    // секретный ключ магазина
    protected string $url;    // URL Telcell (тестовый или боевой)

    public function __construct()
    {
        $this->issuer = config('services.telcell.shop_id');
        $this->key    = config('services.telcell.shop_key');
        $this->url    = config('services.telcell.url'); // например, https://telcellmoney.am/invoices
    }

    /**
     * Создание нового счёта
     */
//     public function createInvoice(string $buyer, float $sum, string $description, string $issuerId, int $validDays = 1, ?string $info = null): array
// {
//     // Base64-кодирование
//     $productEncoded  = base64_encode("SWpldmFubWFya2V0"); // Base64 от "IjevanMarket"
//     $issuer_id = (string)$issuerId; // ID заказа как строка

//     // Контрольная сумма (security_code)
//     $checksumString = $this->key .
//                       $this->issuer .
//                       '51' .               // валюта
//                       $sum .
//                       $productEncoded .
//                       $issuer_id .
//                       $validDays;

//     $checksum = md5($checksumString);

//     // POST-данные
//     $postData = [
//         'action'       => 'PostInvoice',
//         'bill:issuer'  => $this->issuer,
//         'buyer'        => $buyer,
//         'currency'     => 51,
//         'price'        => number_format($sum, 2, '.', ''),
//         'product'      => $productEncoded,
//         'issuer_id'    => $issuer_id,
//         'valid_days'   => $validDays,
//         'security_code'=> $checksum,
//     ];

//     if ($info) {
//         $postData['info'] = base64_encode($info);
//     }
//     Log::info('Telcell POST Request:', $postData);

//     // Отправка запроса
//     $response = Http::asForm()->post($this->url, $postData);

//     Log::info('Telcell Response:', ['body' => $response->body(), 'status' => $response->status()]);

//     return $response->json() ?: [];
// }
// public function createInvoice(string $buyer, float $sum, string $description, string $issuerId, int $validDays = 1, ?string $info = null): array
// {
//     // Base64-кодирование
//     $productEncoded = 'SWpldmFubWFya2V0'; // Base64 от названия магазина
//     $issuerIdEncoded = base64_encode((string)$issuerId);     // Base64 от ID заказа

//     // Валюта — символ драма
//     $currency = '51';

//     // Контрольная сумма (security_code)
//     $checksumString = $this->key .
//                 $this->issuer .
//                 $currency .
//                 number_format($sum, 2, '.', '') .
//                 $productEncoded .
//                 $issuerIdEncoded .
//                 $validDays;


//     $checksum = md5($checksumString);
//     \Log::info('Telcell Checksum String: '.$checksumString);
//     \Log::info('Telcell MD5: '.$checksum);

//     // POST-данные
//     $postData = [
//         'action'       => 'PostInvoice',           // с маленькой 'i' согласно примеру
//         'bill:issuer'  => $this->issuer,
//         'buyer'        => $buyer,
//         'currency'     => $currency,
//         'price' => (string) intval($sum),
//         'product'      => $productEncoded,
//         'issuer_id'    => $issuerIdEncoded,
//         'valid_days'   => $validDays,
//         'security_code'=> $checksum,
//         'lang'         => 'am',
//     ];

//     if ($info) {
//         $postData['info'] = base64_encode($info);
//     }

//     \Log::info('Telcell POST Request:', $postData);

//     // Отправка запроса
//     $response = Http::asForm()->post($this->url, $postData);

//     \Log::info('Telcell Response:', ['body' => $response->body(), 'status' => $response->status()]);

//     return $response->json() ?: [];
// }
public function createInvoice(string $buyer, float $sum, int $orderId, int $validDays = 1, ?string $info = null): array
{
     // Находим заказ по ID
    $order = \App\Models\Order::findOrFail($orderId);

    $issuer = $this->issuer; // Email магазина
    $shopKey = $this->key;   // Секретный ключ магазина
    $currency = '֏';

     // Берём сумму с учётом доставки
    $sum = $order->getTotalForPayment();

    // Base64 описание продукта и ID заказа
    $productEncoded  = base64_encode("IjevanMarket");
    $issuerIdEncoded = base64_encode((string)$orderId);

    // Контрольная сумма
    $checksumString = $shopKey .
                      $issuer .
                      $currency .
                      number_format($sum, 2, '.', '') .
                      $productEncoded .
                      $issuerIdEncoded .
                      $validDays;

    $securityCode = md5($checksumString);

    // POST-данные
    $postData = [
        'action'       => 'PostInvoice',
        'issuer'       => $issuer,
        'currency'     => $currency,
        'price'        => number_format($sum, 2, '.', ''),
        'product'      => $productEncoded,
        'issuer_id'    => $issuerIdEncoded,
        'valid_days'   => $validDays,
        'security_code'=> $securityCode,
        'lang'         => 'am',
        'buyer'        => $buyer, // номер покупателя
        'successUrl'   => route('payment.return', ['order' => $orderId], true),
        'failUrl'      => route('payment.return', ['order' => $orderId], true),
        'callbackUrl' => route('payment.callback', [], true),
    ];

    if ($info) {
        $postData['info'] = base64_encode($info);
    }

    Log::info('Telcell POST Request:', $postData);

    // Отправка запроса
    $response = Http::asForm()->post('https://telcellmoney.am/invoices', $postData);

    // Log::info('Telcell Response:', ['body' => $response->body(), 'status' => $response->status()]);

    return $postData;
}
    public function createInvoiceHtml(string $buyer, float $sum, int $orderId): string
    {
        $invoiceData = $this->createInvoice($buyer, $sum, $orderId);

        $html = '<form id="telcellForm" action="https://telcellmoney.am/invoices" method="POST">';
        foreach ($invoiceData as $key => $value) {
            $html .= '<input type="hidden" name="'.htmlspecialchars($key).'" value="'.htmlspecialchars($value).'">';
        }
        $html .= '</form>';
        $html .= '<script>document.getElementById("telcellForm").submit();</script>';

        return $html;
    }


    /**
     * Проверка статуса счёта
     */
    public function checkStatus(string $invoiceId, ?string $issuerId = null): array
    {
        $checksumString = $this->key . $this->issuer . $invoiceId . ($issuerId ?? '');
        $checksum = md5($checksumString);

        $response = Http::asForm()->post($this->url, [
            'action'    => 'CheckInvoice',
            'bill:issuer' => $this->issuer,
            'invoice'   => $invoiceId,
            'issuer_id' => $issuerId,
            'checksum'  => $checksum,
        ]);

        return $response->json();
    }
    /**
     * Проверка подписи callback-а
     */
    public function verifyCallback(array $data): bool
    {
        $checksum = md5(
            $this->key .
            $data['invoice'] .
            $data['issuer_id'] .
            $data['payment_id'] .
            $data['currency'] .
            $data['sum'] .
            $data['time'] .
            $data['status']
        );

        return $checksum === ($data['checksum'] ?? null);
    }

   public function cancelBill(\App\Models\Order $order)
{
    if (!$order->invoice_id) {
        \Log::error('CancelBill failed: invoice_id is missing', ['order_id' => $order->id]);
        return null;
    }

    $issuer_id = base64_encode($order->id);
    $invoice = $order->invoice_id;
    $shopKey = config('services.telcell.shop_key');

    $checksum = md5($shopKey . $order->issuer . $invoice . $issuer_id);

    \Log::info('Telcell cancelBill request', [
        'invoice' => $invoice,
        'issuer_id' => $issuer_id,
        'checksum' => $checksum,
    ]);

    $response = Http::asForm()->post('https://telcellmoney.am/invoices', [
        'cancel_bill:issuer' => $order->issuer,
        'invoice' => $invoice,
        'issuer_id' => $issuer_id,
        'checksum' => $checksum
    ]);

    \Log::info('Telcell cancelBill response', ['body' => $response->body()]);

    return $response->json();
}


    public function refundBill(\App\Models\Order $order, $refundSum)
    {
        $issuer_id = base64_encode($order->id);
        $invoice = $order->invoice_id;
        $shopKey = config('services.telcell.shop_key');

        $checksum = md5($shopKey . $order->issuer . $invoice . $issuer_id . $refundSum);

        $response = Http::asForm()->post('https://telcellmoney.am/invoices', [
            'refund_bill:issuer' => $order->issuer,
            'invoice' => $invoice,
            'issuer_id' => $issuer_id,
            'refund_sum' => $refundSum,
            'checksum' => $checksum
        ]);

        return $response->json();
    }

}
