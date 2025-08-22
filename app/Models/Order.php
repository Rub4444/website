<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'name', 'phone', 'email', 'status', 'cancellation_comment', 'currency_id', 'sum', 'coupon_id', 'delivery_type', 'delivery_city',
    'delivery_street',
    'delivery_home',];

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
        return $query->where('status', 1);
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


    public function saveOrder($name, $phone, $email, $deliveryType = 'pickup', $delivery_city = null, $delivery_street = null, $delivery_home = null)
    {
        $this->name = $name;
        $this->phone = $phone;
        $this->email = $email;
        $this->delivery_type = $deliveryType;
        $this->delivery_city = $delivery_city;
        $this->delivery_street = $delivery_street;
        $this->delivery_home = $delivery_home;
        $this->status = 1;

        $this->sum = $this->getFullSum();

        if ($this->delivery_type === 'delivery')
        {
            if($this->sum < 10000)
            {
                $this->sum += 500;
            }
        }

        $skus = $this->skus;
        $this->save();

        foreach ($skus as $skuInOrder) {
            $this->skus()->attach($skuInOrder, [
                'count' => $skuInOrder->countInOrder,
                'price' => $skuInOrder->price,
            ]);
        }

        session()->forget('order');
        return true;
    }

    public function hasCoupon()
    {
        return $this->coupon;
    }

}
