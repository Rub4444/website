<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelcellService
{
    protected string $shopId;
    protected string $shopKey;
    protected string $url;

    public function __construct()
    {
        $this->shopId  = config('services.telcell.shop_id');
        $this->shopKey = config('services.telcell.shop_key');
        $this->url     = config('services.telcell.url');
    }

    /**
     * Создание счета Telcell
     */
    public function createInvoice(Order $order, string $buyer): array
    {
        if ($order->invoice_status === 'CREATED') {
            Log::warning('Telcell invoice already exists', [
                'order_id' => $order->id,
                'issuer_id' => $order->issuer_id,
            ]);

            return [];
        }

        // безопасный issuer_id
        $issuerId = base64_encode($order->id . '|' . now()->timestamp);

        $amount   = number_format($order->getTotalForPayment(), 2, '.', '');
        $currency = '֏';
        $product  = base64_encode('IjevanMarket');

        $checksum = md5(
            $this->shopKey .
            $this->shopId .
            $currency .
            $amount .
            $product .
            $issuerId .
            1
        );

        $payload = [
            'action'        => 'PostInvoice',
            'issuer'        => $this->shopId,
            'currency'      => $currency,
            'price'         => $amount,
            'product'       => $product,
            'issuer_id'     => $issuerId,
            'valid_days'    => 1,
            'buyer'         => $buyer,
            'security_code' => $checksum,
            'lang'          => 'am',
            'successUrl'    => route('payment.return', ['order' => $order->id, 'status' => 'success'], true),
            'failUrl'       => route('payment.return', ['order' => $order->id, 'status' => 'fail'], true),
            'callbackUrl'   => route('payment.callback', [], true),
        ];

        $response = Http::asForm()->post($this->url, $payload);

        if (!$response->successful()) {
            Log::error('Telcell invoice failed', ['response' => $response->body()]);
            throw new \RuntimeException('Telcell invoice error');
        }

        Log::info('Telcell invoice created', [
            'order_id'   => $order->id,
            'issuer_id'  => $issuerId,
            'amount'     => $amount,
            'buyer'      => $buyer,
        ]);


        $order->update([
            'issuer_id'      => $issuerId,
            'invoice_id'     => null,
            'invoice_status' => 'CREATED',
        ]);

        return $payload;
    }

    /**
     * Проверка подписи callback
     */
    public function verifyCallback(array $data): bool
    {
        if (!isset($data['checksum'], $data['invoice'], $data['issuer_id'])) {
            return false;
        }

        $expected = md5(
            $this->shopKey .
            $data['invoice'] .
            $data['issuer_id'] .
            ($data['payment_id'] ?? '') .
            ($data['buyer'] ?? '') .
            ($data['currency'] ?? '') .
            ($data['sum'] ?? '') .
            ($data['time'] ?? '') .
            ($data['status'] ?? '')
        );
        // Log::info('TEST_CHECKSUM_DEBUG', [
        //     'expected' => md5(
        //         config('services.telcell.shop_key') .
        //         request('invoice') .
        //         request('issuer_id') .
        //         '' .
        //         '' .
        //         request('currency') .
        //         request('sum') .
        //         request('time') .
        //         'REJECTED'
        //     )
        // ]);



        return hash_equals($expected, $data['checksum']);
    }

    /**
     * HTML-форма с автосабмитом (редирект на Telcell)
     */
    public function createInvoiceHtml(Order $order, string $buyer): string
    {
        $payload = $this->createInvoice($order, $buyer);

        $html = '<form id="telcellForm" method="POST" action="'.$this->url.'">';

        foreach ($payload as $key => $value) {
            $html .= '<input type="hidden" name="'.$key.'" value="'.htmlspecialchars($value).'">';
        }

        $html .= '</form>';
        $html .= '<script>document.getElementById("telcellForm").submit();</script>';

        return $html;
    }

}
