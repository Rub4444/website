<?php

namespace App\Classes;

use App\Models\Order;
use App\Models\Sku;
use App\Models\Coupon;
use App\Mail\OrderCreated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Services\sConversion;

class Basket
{
    protected $order;

    public function __construct($createOrder = false)
    {
        $order = session('order');

        if (is_null($order) && $createOrder) {
            $data = [];
            if (Auth::check()) {
                $data['user_id'] = Auth::id();
            }
            $data['currency_id'] = 1;
            $this->order = new Order($data);
            session(['order' => $this->order]);
        }
        else
        {
            $this->order = $order;
        }
    }



    public function getOrder()
    {
        return $this->order;
    }

    public function countAvailable($updateCount = false)
    {
        $skus = collect([]);
        foreach ($this->order->skus as $orderSku)
        {
            $sku = Sku::find($orderSku->id);
            if ($orderSku->countInOrder > $sku->count)
            {
                return false;
            }
            if($updateCount)
            {
                $sku->count -= $orderSku->countInOrder;
                $skus->push($sku);
            }
        }
        if($updateCount)
        {
            $skus->map->save();
        }
        return true;
    }

    public function saveOrder($name, $phone, $email, $deliveryType, $delivery_city = null, $delivery_street = null, $delivery_home = null)
{
    if (!$this->countAvailable(true)) {
        return false;
    }

    $order = $this->order;

    // 1️⃣ Сохраняем заказ
    $order->name = $name;
    $order->phone = $phone;
    $order->email = $email;
    $order->delivery_type = $deliveryType;
    $order->delivery_city = $delivery_city;
    $order->delivery_street = $delivery_street;
    $order->delivery_home = $delivery_home;
    $order->status = 1;
    $order->sum = $order->getFullSum();
    $order->save(); // <- теперь заказ точно сохраняется в базе

    // 2️⃣ Привязываем товары через pivot
    foreach ($order->skus as $sku) {
        $order->skus()->attach($sku, [
            'count' => $sku->countInOrder,
            'price' => $sku->price,
        ]);
    }

    // 3️⃣ Отправка уведомлений
    Mail::to($email)->send(new OrderCreated($name, $order));
    Mail::to("isahakyan06@gmail.com")->send(new OrderCreated($name, $order));

    // 4️⃣ Очищаем сессию
    session()->forget('order');

    return true;
}


public function removeSku(Sku $sku, $quantity = null)
{
    $quantity = $quantity ?? ($sku->unit === 'kg' ? 0.1 : 1);

    if ($this->order->skus->contains($sku)) {
        $pivotRow = $this->order->skus->where('id', $sku->id)->first();

        $pivotRow->countInOrder -= $quantity;
        if ($pivotRow->countInOrder <= 0) {
            $this->order->skus = $this->order->skus->filter(fn($s) => $s->id !== $sku->id);
        }
    }
}


    public function addSku(Sku $sku, $quantity = null)
{
    $quantity = $quantity ?? ($sku->unit === 'kg' ? 0.5 : 1); // default 0.5kg или 1pcs

    if ($this->order->skus->contains($sku)) {
        $pivotRow = $this->order->skus->where('id', $sku->id)->first();
        // Проверяем, чтобы не превышать доступный count для pcs
        if ($sku->unit === 'pcs' && $pivotRow->countInOrder + $quantity > $sku->count) {
            return false;
        }
        $pivotRow->countInOrder += $quantity;
    } else {
        if ($sku->unit === 'pcs' && $quantity > $sku->count) {
            return false;
        }
        $sku->countInOrder = $quantity;
        $sku->unit = $sku->unit; // сохраняем единицу в объекте SKU для корзины
        $this->order->skus->push($sku);
    }

    return true;
}


    public function setCoupon(Coupon $coupon)
    {
        $this->order->coupon()->associate($coupon);
    }

    public function clearCoupon()
    {
        $this->order->coupon()->dissociate();
    }
    public function setUserId($userId)
{
    $this->order->user_id = $userId;
}

}
