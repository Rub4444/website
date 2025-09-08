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
    protected $order;

    public function __construct($createOrder = false)
    {
        $orderId = session('order_id');

        if (is_null($orderId) && $createOrder) {
            $data = ['currency_id' => 1];
            if (Auth::check()) {
                $data['user_id'] = Auth::id();
            }
            $order = new Order($data);
            $order->save(); // сохраняем, чтобы был ID
            session(['order_id' => $order->id]);
            $this->order = $order;
        } else {
            $this->order = Order::find($orderId);
            if (!$this->order && $createOrder) {
                $this->order = new Order(['currency_id' => 1]);
                if (Auth::check()) {
                    $this->order->user_id = Auth::id();
                }
                $this->order->save();
                session(['order_id' => $this->order->id]);
            }
        }
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function addSku(Sku $sku, $quantity = null)
    {
        $unit = $sku->product->unit ?? 'pcs';
        $quantity = $quantity ?? ($unit === 'kg' ? 0.5 : 1);

        $orderSkus = collect($this->order->skus);

        if ($orderSkus->contains($sku)) {
            $pivot = $orderSkus->where('id', $sku->id)->first();
            if ($unit === 'pcs' && ($pivot->pivot->count + $quantity > $sku->count)) {
                return false;
            }
            $pivot->pivot->count += $quantity;
        } else {
            if ($unit === 'pcs' && $quantity > $sku->count) {
                return false;
            }
            $this->order->skus()->attach($sku->id, [
                'count' => $quantity,
                'price' => $sku->price,
            ]);
        }

        return true;
    }

    public function removeSku(Sku $sku, $quantity = null)
    {
        $unit = $sku->product->unit ?? 'pcs';
        $quantity = $quantity ?? ($unit === 'kg' ? 0.1 : 1);

        $orderSkus = collect($this->order->skus);
        if ($orderSkus->contains($sku)) {
            $pivot = $orderSkus->where('id', $sku->id)->first();
            $newCount = $pivot->pivot->count - $quantity;
            if ($newCount <= 0) {
                $this->order->skus()->detach($sku->id);
            } else {
                $this->order->skus()->updateExistingPivot($sku->id, ['count' => $newCount]);
            }
        }
    }

    public function countAvailable($updateCount = false): bool
    {
        foreach ($this->order->skus as $orderSku) {
            $sku = Sku::find($orderSku->id);
            if ($orderSku->pivot->count > $sku->count) {
                return false;
            }
            if ($updateCount) {
                $sku->count -= $orderSku->pivot->count;
                $sku->save();
            }
        }
        return true;
    }

    public function saveOrder($name, $phone, $email, $deliveryType, $deliveryCity = null, $deliveryStreet = null, $deliveryHome = null)
    {
        if (!$this->countAvailable(true)) {
            return false;
        }

        $order = $this->order->exists
            ? Order::find($this->order->id)
            : $this->order;

        $order->name = $name;
        $order->phone = $phone;
        $order->email = $email;
        $order->delivery_type = $deliveryType;
        $order->delivery_city = $deliveryCity;
        $order->delivery_street = $deliveryStreet;
        $order->delivery_home = $deliveryHome;
        $order->status = 1;
        $order->sum = $order->getFullSum();
        $order->save();

        // Очистка старых связей
        $order->skus()->detach();

        // Сохраняем товары через pivot
        foreach ($this->order->skus as $sku) {
            $order->skus()->attach($sku->id, [
                'count' => $sku->pivot->count ?? $sku->countInOrder,
                'price' => $sku->price,
            ]);
        }

        // Отправка уведомлений
        Mail::to($email)->send(new OrderCreated($name, $order));
        Mail::to("isahakyan06@gmail.com")->send(new OrderCreated($name, $order));

        // Сохраняем только ID заказа в сессии
        session(['order_id' => $order->id]);

        return true;
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

    public function setUserId($userId)
    {
        $this->order->user_id = $userId;
        $this->order->save();
    }

    public function clearBasket()
    {
        session()->forget('order_id');
        $this->order = null;
    }
}
