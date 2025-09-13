<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class TelcellService
{
    protected string $issuer;
    protected string $key;
    protected string $url;

    public function __construct()
    {
        $this->issuer = config('services.telcell.shop_id'); // твой shop_id
        $this->key    = config('services.telcell.shop_key'); // твой shop_key
        $this->url    = 'https://telcellmoney.am/invoices';
    }

    /**
     * Создание счета и сохранение issuer_id в заказе
     */
    public function createInvoice(string $buyer, float $sum, int $orderId, int $validDays = 1, ?string $info = null): array
{
    $order = Order::findOrFail($orderId);

    $currency = '֏';
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
        'callbackUrl'   => route('payment.callback', [], true),
        // 'successUrl'    => route('payment.return', ['order' => $orderId], true),
        // 'failUrl'       => route('payment.return', ['order' => $orderId], true),
        'successUrl' => route('payment.return', ['order' => $orderId, 'status' => 'success'], true),
        'failUrl'    => route('payment.return', ['order' => $orderId, 'status' => 'fail'], true),

    ];

    if ($info) {
        $postData['info'] = base64_encode($info);
    }

    // Log::info('Telcell POST Request:', $postData);

    try {
        Http::asForm()->post($this->url, $postData);
    } catch (\Exception $e) {
        Log::error('Telcell createInvoice failed', ['exception' => $e]);
    }

    // Сохраняем invoice_id, issuer_id и статус заказа
    $order->invoice_id     = $issuerIdEncoded;
    $order->issuer_id      = $issuerIdEncoded; // ✅ новое поле
    $order->invoice_status = 'CREATED';
    $order->save();
    Log::info("TellCellService");
    return $postData;
}

    /**
     * Формирование HTML-формы для оплаты
     */
    public function createInvoiceHtml(string $buyer, float $sum, int $orderId): string
    {
        Log::info("TellCellServiceHTML");

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
        Log::info("verifyCallback");

        $checksum = md5(
            $this->key .
            $data['invoice'] .
            $data['issuer_id'] .
            ($data['payment_id'] ?? '') .
            ($data['buyer'] ?? '') .
            ($data['currency'] ?? '') .
            ($data['sum'] ?? '') .
            ($data['time'] ?? '') .
            ($data['status'] ?? '')
        );

        return $checksum === ($data['checksum'] ?? null);
    }

    /**
     * Обработка callback-а от Telcell
     */
    public function handleCallback(Request $request)
    {
        Log::info("handleCallback");

        $data = $request->all();

        if (!$this->verifyCallback($data))
        {
            return response('Invalid checksum', 400);
        }

        $orderId = base64_decode($data['issuer_id']);
        $order = Order::find($orderId);

        if (!$order) return response('Order not found', 404);

        $order->invoice_status = $data['status'];
        $order->save();

        return response('OK', 200);
    }
     public function checkInvoiceStatus(int $orderId): string
    {
        $url = "https://telcell.am/api"; // реальный URL API Telcell

        $payload = [
            'action' => 'CheckInvoiceStatus',
            'issuer' => $this->shopId,
            'issuer_id' => base64_encode($orderId), // если у Telcell нужен order_id
            'security_code' => $this->shopKey,
        ];

        $response = Http::post($url, $payload);

        if (!$response->successful()) {
            throw new Exception('Ошибка при обращении к Telcell: ' . $response->body());
        }

        $data = $response->json();

        if (!isset($data['status'])) {
            throw new Exception('Не удалось получить статус инвойса');
        }

        // Возможные статусы: PAID, REJECTED, PENDING
        return strtoupper($data['status']);
    }
}
