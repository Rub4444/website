<?php

namespace App\Classes;

use App\Models\Order;
use App\Models\Sku;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCreated;
use Illuminate\Support\Collection;

class Basket
{
    protected Order $order;
    protected Collection $basketSkus;

    public function __construct($createOrder = false)
    {
        $orderId = session('order_id');
        $skus = session('basket_skus', collect());

        $this->basketSkus = $skus;

        if ($orderId) {
            $this->order = Order::find($orderId) ?? new Order();
        } elseif ($createOrder) {
            $data = ['currency_id' => 1];
            if (Auth::check()) $data['user_id'] = Auth::id();
            $this->order = new Order($data);
        } else {
            $this->order = new Order();
        }
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function getSkus(): Collection
    {
        return $this->basketSkus;
    }

    public function addSku(Sku $sku, float|int $quantity = null): bool
    {
        $unit = $sku->product->unit;
        $quantity ??= ($unit === 'kg' ? 0.5 : 1);

        $existing = $this->basketSkus->firstWhere('id', $sku->id);

        if ($existing) {
            if ($unit === 'pcs' && $existing->countInOrder + $quantity > $sku->count) {
                return false;
            }
            $existing->countInOrder += $quantity;
        } else {
            if ($unit === 'pcs' && $quantity > $sku->count) return false;

            $sku->countInOrder = $quantity;
            $sku->unit = $unit;
            $this->basketSkus->push($sku);
        }

        session(['basket_skus' => $this->basketSkus]);
        return true;
    }

    public function removeSku(Sku $sku, float|int $quantity = null)
    {
        $unit = $sku->product->unit;
        $quantity ??= ($unit === 'kg' ? 0.1 : 1);

        $existing = $this->basketSkus->firstWhere('id', $sku->id);

        if ($existing) {
            $existing->countInOrder -= $quantity;
            if ($existing->countInOrder <= 0) {
                $this->basketSkus = $this->basketSkus->filter(fn($s) => $s->id !== $sku->id);
            }
            session(['basket_skus' => $this->basketSkus]);
        }
    }

    public function clearBasket()
    {
        $this->basketSkus = collect();
        session()->forget('basket_skus');
    }

    public function countAvailable(bool $updateCount = false): bool
    {
        foreach ($this->basketSkus as $sku) {
            $dbSku = Sku::find($sku->id);
            if ($sku->countInOrder > $dbSku->count) return false;

            if ($updateCount) {
                $dbSku->count -= $sku->countInOrder;
                $dbSku->save();
            }
        }
        return true;
    }

    public function saveOrder(
        string $name,
        string $phone,
        string $email,
        string $deliveryType,
        ?string $delivery_city = null,
        ?string $delivery_street = null,
        ?string $delivery_home = null
    ): Order|false {
        if (!$this->countAvailable(true)) return false;

        $order = $this->order;
        $order->name = $name;
        $order->phone = $phone;
        $order->email = $email;
        $order->delivery_type = $deliveryType;
        $order->delivery_city = $delivery_city;
        $order->delivery_street = $delivery_street;
        $order->delivery_home = $delivery_home;
        $order->status = Order::STATUS_PENDING;

        $order->sum = max(0, $this->getFullSum());
        if ($deliveryType === 'delivery' && $order->sum < 10000) {
            $order->sum += 500;
        }

        $order->save();

        // Pivot attach
        foreach ($this->basketSkus as $sku) {
            $order->skus()->attach($sku->id, [
                'count' => $sku->countInOrder,
                'price' => $sku->price,
            ]);
        }

        Mail::to($email)->send(new OrderCreated($name, $order));

        $this->clearBasket();
        session(['order_id' => $order->id]);

        return $order;
    }

    public function getFullSum(): float
    {
        $sum = 0;
        foreach ($this->basketSkus as $sku) {
            $sum += $sku->price * $sku->countInOrder;
        }
        if ($this->order->hasCoupon()) {
            $sum = $this->order->coupon->applyCost($sum, $this->order->currency);
        }
        return $sum;
    }

    public function setCoupon(Coupon $coupon)
    {
        $this->order->coupon()->associate($coupon);
        $this->order->save();
    }

    public function clearCoupon()
    {
        $this->order->coupon()->dissociate();
        $this->order->save();
    }
}
