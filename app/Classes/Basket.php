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

        if (is_null($order) && $createOrder)
        {
            $data = [];

            if (Auth::check())
            {
                $data['user_id'] = Auth::id();
            }

            $data['currency_id'] = 1;

            $this->order = new Order($data);

            session(['order' => $this->order]);

            // 👇 Добавляем пакет один раз при создании новой корзины
            $this->addPackageSku();
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
        if (!$this->countAvailable(true)) return false;

        $order = $this->order;

        //Skus Insert INto
        // unset($order->skus);

        $order->name = $name;
        $order->phone = $phone;
        $order->email = $email;
        $order->delivery_type = $deliveryType;
        $order->delivery_city = $delivery_city;
        $order->delivery_street = $delivery_street;
        $order->delivery_home = $delivery_home;
        $order->status = 1;
        $order->sum = $order->getFullSum();
        $order->save(); // Сохраняем сам заказ

        // Привязываем товары через pivot
        // foreach ($this->order->skus as $sku)
        foreach ($order->skus as $sku)
        {
            $order->skus()->attach($sku->id, [
                'count' => $sku->countInOrder,
                'price' => $sku->price,
            ]);
        }

        Mail::to($email)->send(new OrderCreated($name, $order));

        // session(['order_id' => $order->id]);
        session()->forget('order');
        return true;
    }



public function removeSku(Sku $sku, $quantity = null)
{
    // Проверяем единицу измерения у продукта
    $unit = $sku->product->unit;

    $quantity = $quantity ?? ($unit === 'kg' ? 0.1 : 1);

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
    $unit = $sku->product->unit; // берём unit у продукта
    $quantity = $quantity ?? ($unit === 'kg' ? 0.5 : 1); // default 0.5kg или 1шт

    if ($this->order->skus->contains($sku))
    {
        $pivotRow = $this->order->skus->where('id', $sku->id)->first();

        // Проверяем, чтобы не превышать доступный count для шт
        if ($unit === 'pcs' && $pivotRow->countInOrder + $quantity > $sku->count)
        {
            return false;
        }

        $pivotRow->countInOrder += $quantity;
    }
    else
    {
        if ($unit === 'pcs' && $quantity > $sku->count)
        {
            return false;
        }

        $sku->countInOrder = $quantity;
        $sku->unit = $unit; // сохраняем единицу для корзины
        $this->order->skus->push($sku);
    }
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

    protected function addPackageSku()
    {
        // ID пакета лучше вынести в .env или config
        $packageSkuId = config('app.package_sku_id');

        $sku = Sku::find($packageSkuId);

        if ($sku)
        {
            $this->addSku($sku, 1); // добавляем 1 пакет
        }
    }

}
