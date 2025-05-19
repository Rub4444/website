<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Sku;
use App\Models\Coupon;
use App\Http\Requests\AddCouponRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Classes\Basket;
use App\Models\Category;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCreated;


class BasketController extends Controller
{
    public function basket()
    {
        $order = (new Basket())->getOrder();
        $categories = Category::all();
        return view('basket', compact('order', 'categories'));
    }

    public function basketConfirm(Request $request)
{

     $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:50',
        'email' => 'required|email',
        'delivery_type' => 'required|in:pickup,courier',
        'address' => 'required_if:delivery_type,courier|max:255',
        'latitude' => 'required_if:delivery_type,courier|numeric',
        'longitude' => 'required_if:delivery_type,courier|numeric',
    ]);

    $basket = new Basket();

    if ($basket->getOrder()->hasCoupon() && !$basket->getOrder()->coupon->availableForUse()) {
        $basket->clearCoupon();
        session()->flash('warning', 'Купон не доступен!');
        return redirect()->route('basket');
    }

    $email = Auth::check() ? Auth::user()->email : $request->email;
    $deliveryType = $request->delivery_type;
    $address = $deliveryType === 'courier' ? $request->address : null;
    $latitude = $request->latitude;
    $longitude = $request->longitude;

    if ($basket->saveOrder(
        $request->name,
        $request->phone,
        $email,
        $deliveryType,
        $address,
        $latitude,
        $longitude ))
    {
        $order = $basket->getOrder();
        Mail::to($email)->send(new OrderCreated($request->name, $order));
        session()->flash('success', __('basket.your_order_confirmed'));
    }
    else
    {
        session()->flash('warning', __('basket.cant_find_product'));
    }

    return redirect()->route('index');
}


    public function basketPlace()
    {
        $basket = new Basket();
        $order = $basket->getOrder();
        if(!$basket->countAvailable())
        {
            session()->flash('warning', __('basket.cant_find_product'));
            return redirect()->route('basket');
        }
        $categories = Category::all();
        return view('order', compact('order', 'categories'));
    }

    public function basketAdd(Sku $skus)
    {
        $result = (new Basket(true))->addSku($skus);
        if($result)
        {
            session()->flash('success', __('basket.basket_product') . ' ' . $skus->product->name . ' ' . __('basket.basket_add'));
        }
        else
        {
            session()->flash('warning',  __('basket.basket_product') . ' ' . $skus->product->name . ' ' . __('basket.basket_not_add'));
        }
        return redirect()->route('basket');
    }

    public function basketRemove(Sku $skus)
    {
        (new Basket())->removeSku($skus);

        session()->flash('warning', __('basket.removed').$skus->product->__('name'));

        return redirect()->route('basket');
    }


    public function setCoupon(AddCouponRequest $request)
    {
        $coupon = Coupon::where('code', $request->coupon)->first();
        if($coupon->availableForUse())
        {
            (new Basket())->setCoupon($coupon);
            session()->flash('success', __('basket.coupon_added'));
        }
        else
        {
             session()->flash('warning', __('basket.coupon_not_added'));
        }

        return redirect()->route('basket');
    }
}
