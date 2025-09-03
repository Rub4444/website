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
public function createInvoice(string $buyer, float $sum, string $description, string $issuerId, int $validDays = 1, ?string $info = null): array
{
    // Base64-кодирование
    $productEncoded = 'SWpldmFubWFya2V0'; // Base64 от названия магазина
    $issuerIdEncoded = base64_encode((string)$issuerId);     // Base64 от ID заказа

    // Валюта — символ драма
    $currency = '51';

    // Контрольная сумма (security_code)
    $checksumString = $this->key .
                $this->issuer .
                $currency .
                number_format($sum, 2, '.', '') .
                $productEncoded .
                $issuerIdEncoded .
                $validDays;


    $checksum = md5($checksumString);
    \Log::info('Telcell Checksum String: '.$checksumString);
    \Log::info('Telcell MD5: '.$checksum);

    // POST-данные
    $postData = [
        'action'       => 'PostInvoice',           // с маленькой 'i' согласно примеру
        'bill:issuer'  => $this->issuer,
        'buyer'        => $buyer,
        'currency'     => $currency,
        'price' => (string) intval($sum),
        'product'      => $productEncoded,
        'issuer_id'    => $issuerIdEncoded,
        'valid_days'   => $validDays,
        'security_code'=> $checksum,
        'lang'         => 'am',
    ];

    if ($info) {
        $postData['info'] = base64_encode($info);
    }

    \Log::info('Telcell POST Request:', $postData);

    // Отправка запроса
    $response = Http::asForm()->post($this->url, $postData);

    \Log::info('Telcell Response:', ['body' => $response->body(), 'status' => $response->status()]);

    return $response->json() ?: [];
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
}
