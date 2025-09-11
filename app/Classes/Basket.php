<?php

namespace App\Classes;

use App\Models\Order;
use App\Models\Sku;
use App\Models\Coupon;
use App\Mail\OrderCreated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class Basket
{
    protected Order $order;

    public function __construct(bool $createOrder = false)
    {
        $order = session('order');

        if (is_null($order) && $createOrder) {
            $data = ['currency_id' => 1]; // текущая валюта
            if (Auth::check()) $data['user_id'] = Auth::id();

            $this->order = new Order($data);
            session(['order' => $this->order]);
        } else {
            $this->order = $order;
        }
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function countAvailable(bool $updateCount = false): bool
    {
        foreach ($this->order->skus as $orderSku) {
            $sku = Sku::find($orderSku->id);
            if ($orderSku->pivot->count > $sku->count) return false;

            if ($updateCount) {
                $sku->count -= $orderSku->pivot->count;
                $sku->save();
            }
        }
        return true;
    }

    public function saveOrder(array $data, string $email): bool
    {
        if (!$this->countAvailable(true)) return false;

        // Формируем массив товаров для pivot
        $skus = [];
        foreach ($this->order->skus as $sku) {
            $skus[] = [
                'id' => $sku->id,
                'countInOrder' => $sku->countInOrder,
                'price' => $sku->price,
            ];
        }

        $this->order->saveOrder($data, $skus);

        // Отправка письма
        Mail::to($email)->send(new OrderCreated($data['name'], $this->order));

        session()->forget('order');
        return true;
    }

    public function addSku(Sku $sku, float|int $quantity = null): bool
    {
        $quantity ??= ($sku->product->unit === 'kg' ? 0.5 : 1);

        $existing = $this->order->skus->firstWhere('id', $sku->id);
        if ($existing) {
            if (($existing->countInOrder + $quantity) > $sku->count) return false;
            $existing->countInOrder += $quantity;
        } else {
            if ($quantity > $sku->count) return false;
            $sku->countInOrder = $quantity;
            $this->order->skus->push($sku);
        }
        return true;
    }

    public function removeSku(Sku $sku, float|int $quantity = null): void
    {
        $quantity ??= ($sku->product->unit === 'kg' ? 0.1 : 1);

        $existing = $this->order->skus->firstWhere('id', $sku->id);
        if ($existing) {
            $existing->countInOrder -= $quantity;
            if ($existing->countInOrder <= 0) {
                $this->order->skus = $this->order->skus->filter(fn($s) => $s->id !== $sku->id);
            }
        }
    }

    public function setCoupon(Coupon $coupon): void
    {
        $this->order->coupon()->associate($coupon);
    }

    public function clearCoupon(): void
    {
        $this->order->coupon()->dissociate();
    }
}
