<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelcellService
{
    protected string $issuer;
    protected string $key;
    protected string $url;

    public function __construct()
    {
        $this->issuer = config('services.telcell.issuer');
        $this->key = config('services.telcell.key');
        $this->url = config('services.telcell.url');
    }

    /**
     * Создание нового счёта
     */
    public function createInvoice(string $buyer, float $sum, string $description, string $issuerId, int $validDays = 1): ?array
    {
        $descriptionEncoded = base64_encode($description);
        $issuerIdEncoded = base64_encode($issuerId);

        $checksum = md5(
            $this->key .
            $this->issuer .
            $buyer .
            51 . // AMD = 51
            $sum .
            $descriptionEncoded .
            $validDays .
            $issuerIdEncoded
        );

        $response = Http::asForm()->post($this->url, [
            'bill:issuer' => $this->issuer,
            'buyer' => $buyer,
            'currency' => 51,
            'sum' => $sum,
            'description' => $descriptionEncoded,
            'issuer_id' => $issuerIdEncoded,
            'valid_days' => $validDays,
            'checksum' => $checksum,
        ]);

        return $response->json();
    }

    /**
     * Проверка подписи коллбэка
     */
public function verifyCallback(array $data): bool
{
    $checksum = md5(
        $this->key .
        $this->issuer . // вместо $data['invoice']
        $data['issuer_id'] .
        $data['payment_id'] .
        $data['buyer'] .
        $data['currency'] .
        $data['sum'] .
        $data['time'] .
        $data['status']
    );

    return $checksum === ($data['checksum'] ?? null);
}

}
