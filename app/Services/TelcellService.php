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

      try {
        $response = Http::asForm()->post('https://telcellmoney.am/invoices', $postData);
    } catch (\Exception $e) {
        Log::error('Telcell createInvoice failed', ['exception' => $e]);
        return $postData; // возвращаем хотя бы данные для HTML формы
    }

    // Сохраняем invoice_id и статус в заказ
    $order->invoice_id = $issuerIdEncoded;
    $order->invoice_status = 'CREATED';
    $order->save();


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
    public function cancelOrder(\App\Models\Order $order): ?array
{
    if (!$order->invoice_id) {
        Log::error('CancelOrder: invoice_id missing', ['order_id' => $order->id]);
        return null;
    }

    $issuerId = base64_encode((string)$order->id);

    // Получаем текущий статус счета
    $statusResponse = $this->checkStatus($order->invoice_id, $issuerId);
    $status = $statusResponse['status'] ?? null;

    $isPaid = in_array($status, ['PAID', 'PARTIALLY_PAID']);
    $action = $isPaid ? 'cancel_bill:issuer' : 'clear_bill:issuer';

    $params = [
        $action     => $this->issuer,
        'invoice'   => $order->invoice_id,
        'issuer_id' => $issuerId,
        'checksum'  => $isPaid
            ? md5($this->key . $this->issuer . $order->invoice_id . $issuerId)
            : md5($this->key . $this->issuer . $issuerId),
    ];

    Log::info('Telcell cancelOrder request', [
        'order_id' => $order->id,
        'params' => $params
    ]);

    $response = Http::asForm()->post($this->url, $params);

    Log::info('Telcell cancelOrder response', [
        'order_id' => $order->id,
        'status' => $status,
        'body' => $response->body(),
        'http' => $response->status(),
    ]);

    // Если успешно, обновляем статус заказа
    if ($response->successful()) {
        $order->invoice_status = $isPaid ? 'CANCELED' : 'CLEARED';
        $order->save();
    }

    return $response->json();
}


}
