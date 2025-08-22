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
        $basket = new Basket();

        if($basket->getOrder()->hasCoupon() && !$basket->getOrder()->coupon->availableForUse())
        {
            $basket->clearCoupon();
            session()->flash('warning', 'Купон не доступен!');
            return redirect()->route('basket');
        }

        $email = Auth::check() ? Auth::user()->email : $request->email;

        if ($basket->saveOrder($request->name,
        $request->phone,
        $email,
        $request->delivery_type,
        $request->delivery_city,
        $request->delivery_street,
        $request->delivery_home,
        ))
        {
            session()->flash('success', __('basket.your_order_confirmed'));
        } else {
            session()->flash('warning', 'Товар не доступен!');
        }

        return redirect()->route('index');
    }

    public function basketClear()
    {
        $basket = new Basket();
        // Можно создать метод clear() в Basket, который сбросит session('order')
        session()->forget('order');

        session()->flash('success', 'Корзина очищена');
        return redirect()->route('basket');
    }


    public function basketPlace()
    {
        $basket = new Basket();
        $order = $basket->getOrder();
        if(!$basket->countAvailable())
        {
            session()->flash('warning', 'Товар не доступен!');
            return redirect()->route('basket');
        }
        $categories = Category::all();
        return view('order', compact('order', 'categories'));
    }

    public function basketAdd(Request $request, Sku $skus)
{
    $quantity = $request->input('quantity', $skus->unit === 'kg' ? 0.5 : 1);

    $result = (new Basket(true))->addSku($skus, $quantity);

    if($result) {
        session()->flash('success', 'Товар "' . $skus->product->__('name') . '" добавлен в корзину');
    } else {
        session()->flash('warning', 'Товар "' . $skus->product->__('name') . '" не добавлен в корзину');
    }

    return redirect()->route('basket');
}


    public function basketRemove(Request $request, Sku $skus)
{
    $quantity = $request->input('quantity', $skus->unit === 'kg' ? 0.1 : 1);

    (new Basket())->removeSku($skus, $quantity);

    session()->flash('warning', 'Товар "' . $skus->product->__('name') . '" удалён из корзины');
    return redirect()->route('basket');
}

    public function setCoupon(AddCouponRequest $request)
    {
        $coupon = Coupon::where('code', $request->coupon)->first();
        if($coupon->availableForUse())
        {
            (new Basket())->setCoupon($coupon);
            session()->flash('success', 'Купон был добавлен к заказу');
        }
        else
        {
             session()->flash('warning', 'Купон не может быть использован');
        }
        return redirect()->route('basket');
    }
}
