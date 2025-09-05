<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelcellService
{
    protected string $issuer;
    protected string $key;
    protected string $url;

    public function __construct()
    {
        $this->issuer = config('services.telcell.shop_id');
        $this->key    = config('services.telcell.shop_key');
        $this->url    = 'https://telcellmoney.am/invoices';
    }

    /**
     * Создание счета
     */
    public function createInvoice(string $buyer, float $sum, int $orderId, int $validDays = 1, ?string $info = null): array
    {
        $order = Order::findOrFail($orderId);

        $currency = '֏';
        $sum = $order->getTotalForPayment();

        $productEncoded  = base64_encode("IjevanMarket");
        $issuerIdEncoded = base64_encode((string)$orderId);

        $checksumString = $this->key .
            $this->issuer .
            $currency .
            number_format($sum, 2, '.', '') .
            $productEncoded .
            $issuerIdEncoded .
            $validDays;

        $securityCode = md5($checksumString);

        $postData = [
            'action'        => 'PostInvoice',
            'issuer'        => $this->issuer,
            'currency'      => $currency,
            'price'         => number_format($sum, 2, '.', ''),
            'product'       => $productEncoded,
            'issuer_id'     => $issuerIdEncoded,
            'valid_days'    => $validDays,
            'security_code' => $securityCode,
            'lang'          => 'am',
            'buyer'         => $buyer,
            'successUrl'    => route('payment.return', ['order' => $orderId], true),
            'failUrl'       => route('payment.return', ['order' => $orderId], true),
            'callbackUrl'   => route('payment.callback', [], true),
        ];

        if ($info) {
            $postData['info'] = base64_encode($info);
        }

        Log::info('Telcell createInvoice POST:', $postData);

        // Отправляем запрос и получаем invoice_id
        $response = Http::asForm()->post($this->url, $postData);

        Log::info('Telcell createInvoice RESPONSE:', [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);

        $json = $response->json();
        if (!empty($json['invoice'])) {
            $order->update([
                'invoice_id' => $json['invoice'],
                'invoice_status' => $json['status'] ?? 'NEW',
            ]);
        }

        return $postData;
    }

    /**
     * Автоматическая форма для оплаты
     */
    public function createInvoiceHtml(string $buyer, float $sum, int $orderId): string
    {
        $invoiceData = $this->createInvoice($buyer, $sum, $orderId);

        $html = '<form id="telcellForm" action="'.$this->url.'" method="POST">';
        foreach ($invoiceData as $key => $value) {
            $html .= '<input type="hidden" name="'.htmlspecialchars($key).'" value="'.htmlspecialchars($value).'">';
        }
        $html .= '</form>';
        $html .= '<script>document.getElementById("telcellForm").submit();</script>';

        return $html;
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

    /**
     * Проверка статуса счета
     */
    public function checkStatus(string $invoiceId, string $issuerId): ?array
    {
        $checksum = md5($this->key . $this->issuer . $invoiceId . $issuerId);

        $response = Http::asForm()->post($this->url, [
            'action'    => 'GetInvoiceStatus',
            'issuer'    => $this->issuer,
            'invoice'   => $invoiceId,
            'issuer_id' => $issuerId,
            'checksum'  => $checksum,
        ]);

        Log::info('Telcell checkStatus response', [
            'invoice' => $invoiceId,
            'body'    => $response->body(),
            'status'  => $response->status(),
        ]);

        return $response->json();
    }

    /**
     * Отмена или очистка счета
     */
    public function cancelOrder(Order $order): ?array
    {
        if (!$order->invoice_id) {
            Log::error('CancelOrder: invoice_id missing', ['order_id' => $order->id]);
            return null;
        }

        $issuerId = base64_encode($order->id);

        $statusResponse = $this->checkStatus($order->invoice_id, $issuerId);
        $status = $statusResponse['status'] ?? null;

        $isPaid = in_array($status, ['PAID', 'PARTIALLY_PAID']);

        $params = [
            ($isPaid ? 'cancel_bill:issuer' : 'clear_bill:issuer') => $this->issuer,
            'invoice'   => $order->invoice_id,
            'issuer_id' => $issuerId,
            'checksum'  => $isPaid
                ? md5($this->key . $this->issuer . $order->invoice_id . $issuerId)
                : md5($this->key . $this->issuer . $issuerId),
        ];

        $response = Http::asForm()->post($this->url, $params);

        Log::info('Telcell cancelOrder response', [
            'order_id' => $order->id,
            'body'     => $response->body(),
            'status'   => $response->status(),
        ]);

        return $response->json();
    }
}
