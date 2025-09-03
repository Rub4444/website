<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

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
    public function createInvoice(string $buyer, float $sum, string $description, string $issuerId, int $validDays = 1, ?string $info = null): array
    {
        // Base64-кодирование текстовых полей
        $descriptionEncoded = base64_encode($description);
        $issuerIdEncoded    = base64_encode($issuerId);
        $infoEncoded        = $info ? base64_encode($info) : null;

        // Формирование контрольной суммы (checksum)
        $checksumString = $this->key .
                  $this->issuer .       // shop_id
                  '51' .                // валюта
                  $sum .                // price
                  $descriptionEncoded . // product
                  $issuerIdEncoded .    // issuer_id
                  $validDays;           // valid_days

        if ($infoEncoded) {
            $checksumString .= $infoEncoded;
        }

        $checksum = md5($checksumString);

        // Формируем тело POST-запроса
        $postData = [
            'action'      => 'PostInvoice',
            'bill:issuer' => $this->issuer,
            'buyer'       => $buyer,
            'currency'    => 51,
            'sum'         => $sum,
            'description' => $descriptionEncoded,
            'issuer_id'   => $issuerIdEncoded,
            'valid_days'  => $validDays,
            'security_code' => $checksum,
        ];

        if ($infoEncoded) {
            $postData['info'] = $infoEncoded;
        }

        // Отправка запроса
        $response = Http::asForm()->post($this->url, $postData);

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
