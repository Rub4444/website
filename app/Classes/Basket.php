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
        if (!$this->countAvailable(true))
        {
            return false;
        }
        $this->order->saveOrder($name, $phone, $deliveryType, $delivery_city, $delivery_street, $delivery_home);
        Mail::to($email)->send(new OrderCreated($name, $this->getOrder()));
        Mail::to("isahakyan06@gmail.com")->send(new OrderCreated($name, $this->getOrder()));
        return true;
    }

  public function removeSku(Sku $sku)
    {
        if ($this->order->skus->contains($sku))
        {
            $pivotRow = $this->order->skus->where('id', $sku->id)->first();
            if ($pivotRow->countInOrder < 2)
            {
                $this->order->skus->pop($sku->id);;
            }
            else
            {
                $pivotRow->countInOrder--;
            }
        }
    }

    public function addSku(Sku $sku)
    {
        if ($this->order->skus->contains($sku)) {
            $pivotRow = $this->order->skus->where('id', $sku->id)->first();
            if ($pivotRow->countInOrder >= $sku->count) {
                return false;
            }
            $pivotRow->countInOrder++;
        } else {
            if ($sku->count == 0) {
                return false;
            }
            $sku->countInOrder = 1;
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
}
