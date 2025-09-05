<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'name', 'phone', 'email', 'status', 'cancellation_comment', 'currency_id', 'sum', 'coupon_id', 'delivery_type', 'delivery_city',
    'delivery_street',
    'delivery_home',
    'invoice_id',
    'invoice_status'];

    // Определяем константы статусов
    public const STATUS_PENDING     = 1; // Заказ принят
    public const STATUS_PAID        = 2; // Заказ оплачен
    public const STATUS_CANCELLED   = 3; // Отменён
    public const STATUS_DELIVERED   = 4; // Доставлен
    public const STATUS_SHIPPED     = 5; // Отправлен / В пути
    // public const STATUS_REFUNDED    = 6; // Vazvrat
    public function setStatus(int $status): void
    {
        $this->status = $status;
        $this->save();
    }

    public function isStatus(int $status): bool
    {
        return $this->status === $status;
    }

    /**
     * Получить человекочитаемое название статуса
     */
    public function getStatusName(): string
{
    $status = (int) $this->status;

    return match($status) {       // <- здесь $status, а не $this->status
        self::STATUS_PENDING => __('order.pending'),
        self::STATUS_PAID => __('order.paid'),
        self::STATUS_SHIPPED => __('order.shipped'),
        self::STATUS_DELIVERED => __('order.delivered'),
        self::STATUS_CANCELLED => __('order.cancelled'),
        default => __('order.unknown'),
    };
}



     // --- Методы для управления статусами ---
    // public function markAsRefunded()
    // {
    //     $this->status = self::STATUS_REFUNDED;
    //     $this->save();
    // }
    public function markAsPending(): void
    {
        $this->update(['status' => self::STATUS_PENDING]);
    }
    public function markAsCancelled(): void
    {
        $this->update(['status' => self::STATUS_CANCELLED]);
    }

    public function markAsPaid(): void
    {
        $this->update(['status' => self::STATUS_PAID]);
    }

    public function markAsShipped(): void
    {
        $this->update(['status' => self::STATUS_SHIPPED]);
    }

    public function markAsDelivered(): void
    {
        $this->update(['status' => self::STATUS_DELIVERED]);
    }

    public function skus()
    {
        return $this->belongsToMany(Sku::class, 'order_sku')
                    ->withPivot('count', 'price')
                    ->withTimestamps();
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }


    public function calculateFullSum()
    {
        $sum = 0;
        foreach ($this->skus()->withTrashed()->get() as $sku)
        {
            $sum += $sku->getPriceForCount();
        }
        return $sum;
    }

    public function getFullSum($withCoupon = true)
    {
        $sum = 0;
        foreach($this->skus as $sku)
        {
            $sum += $sku->price * $sku->countInOrder;
        }

        if($withCoupon && $this->hasCoupon())
        {
            $sum = $this->coupon->applyCost($sum, $this->currency);
        }
        return $sum;
    }
    // В модели Order
public function getTotalForPayment(): int
{
    $total = $this->sum;

    // Если доставка и сумма < 10000, добавляем 500
    if ($this->delivery_type === 'delivery' && $this->sum < 10000) {
        $total += 500;
    }

    return $total;
}

    public function saveOrder($name, $phone, $email, $deliveryType = 'pickup', $delivery_city = null, $delivery_street = null, $delivery_home = null)
    {
        $this->name = $name;
        $this->phone = $phone;
        $this->email = $email;
        $this->delivery_type = $deliveryType;
        $this->delivery_city = $delivery_city;
        $this->delivery_street = $delivery_street;
        $this->delivery_home = $delivery_home;
        $this->status = self::STATUS_PENDING;

        // Защита от отрицательной суммы из-за купонов
        $this->sum = max(0, $this->getFullSum());

        if ($this->delivery_type === 'delivery' && $this->sum < 10000) {
            $this->sum += 500;
        }

        $this->save();

        session()->forget('order');
        return true;
    }
    // public function saveOrder($name, $phone, $email, $deliveryType = 'pickup', $delivery_city = null, $delivery_street = null, $delivery_home = null)
    // {
    //     $this->name = $name;
    //     $this->phone = $phone;
    //     $this->email = $email;
    //     $this->delivery_type = $deliveryType;
    //     $this->delivery_city = $delivery_city;
    //     $this->delivery_street = $delivery_street;
    //     $this->delivery_home = $delivery_home;
    //     $this->status = 1;

    //     $this->sum = $this->getFullSum();

    //     if ($this->delivery_type === 'delivery')
    //     {
    //         if($this->sum < 10000)
    //         {
    //             $this->sum += 500;
    //         }
    //     }

    //     // $skus = $this->skus;
    //     // $this->save();

    //     // foreach ($skus as $skuInOrder) {
    //     //     $this->skus()->attach($skuInOrder, [
    //     //         'count' => $skuInOrder->countInOrder,
    //     //         'price' => $skuInOrder->price,
    //     //     ]);
    //     // }

    //     session()->forget('order');
    //     return true;
    // }

    public function hasCoupon()
    {
        return $this->coupon;
    }

}
