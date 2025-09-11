<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'name', 'phone', 'email', 'status', 'cancellation_comment',
        'currency_id', 'sum', 'coupon_id', 'delivery_type', 'delivery_city',
        'delivery_street', 'delivery_home', 'invoice_id', 'invoice_status', 'issuer_id'
    ];

    // Статусы заказа
    public const STATUS_PENDING   = 1;
    public const STATUS_PAID      = 2;
    public const STATUS_CANCELLED = 3;
    public const STATUS_DELIVERED = 4;
    public const STATUS_SHIPPED   = 5;

    // Связи
    public function skus()
    {
        return $this->belongsToMany(Sku::class, 'order_sku')
                    ->withPivot('count', 'price')
                    ->withTimestamps();
    }

    public function currency() { return $this->belongsTo(Currency::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function coupon() { return $this->belongsTo(Coupon::class); }

    // Проверка и изменение статуса
    public function setStatus(int $status): void
    {
        $this->status = $status;
        $this->save();
    }

    public function isStatus(int $status): bool
    {
        return $this->status === $status;
    }

    public function getStatusName(): string
    {
        return match((int)$this->status) {
            self::STATUS_PENDING   => __('order.pending'),
            self::STATUS_PAID      => __('order.paid'),
            self::STATUS_SHIPPED   => __('order.shipped'),
            self::STATUS_DELIVERED => __('order.delivered'),
            self::STATUS_CANCELLED => __('order.cancelled'),
            default => __('order.unknown'),
        };
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    // Сумма заказа без купона
    public function calculateFullSum(): float
    {
        $sum = 0;
        foreach ($this->skus()->withTrashed()->get() as $sku) {
            $sum += $sku->getPriceForCount();
        }
        return $sum;
    }

    // Сумма с купоном
    public function getFullSum(bool $withCoupon = true): float
    {
        $sum = 0;
        foreach ($this->skus as $sku) {
            $sum += $sku->pivot->price * $sku->pivot->count;
        }

        if ($withCoupon && $this->coupon) {
            $sum = $this->coupon->applyCost($sum, $this->currency);
        }

        return $sum;
    }

    // Общая сумма для оплаты с учётом доставки
    public function getTotalForPayment(): float
    {
        $total = $this->sum;
        if ($this->delivery_type === 'delivery' && $total < 10000) {
            $total += 500;
        }
        return $total;
    }

    // Сохраняем заказ и привязываем товары через pivot
    public function saveOrder(array $data, array $skus = []): bool
    {
        $this->fill($data);
        $this->status = self::STATUS_PENDING;
        $this->save();

        if (!empty($skus)) {
            foreach ($skus as $sku) {
                $this->skus()->attach($sku['id'], [
                    'count' => $sku['countInOrder'],
                    'price' => $sku['price'],
                ]);
            }
        }

        // Пересчёт суммы
        $this->sum = $this->getFullSum();
        if ($this->delivery_type === 'delivery' && $this->sum < 10000) {
            $this->sum += 500;
        }
        $this->save();

        return true;
    }
}
