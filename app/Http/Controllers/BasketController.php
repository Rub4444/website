<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Sku;
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
        $email = Auth::check() ? Auth::user()->email : $request->email;
        if ((new Basket())->saveOrder($request->name, $request->phone, $email))
        {
            session()->flash('success', __('basket.your_order_confirmed'));
        } else {
            session()->flash('warning', 'Товар не доступен!');
        }

        return redirect()->route('index');
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

    public function basketAdd(Sku $skus)
    {
        $result = (new Basket(true))->addSku($skus);
        if($result)
        {
            session()->flash('success', 'Товар "' . $skus->product->__('name') . '" добавлен в корзину');
        }
        else
        {
            session()->flash('warning', 'Товар "' . $skus->product->__('name') . '" не добавлен в корзину');
        }
        return redirect()->route('basket');
    }

    public function basketRemove(Sku $skus)
    {
        (new Basket())->removeSku($skus);

        session()->flash('warning', 'Товар "' . $skus->product->__('name') . '" удалён из корзины');
        return redirect()->route('basket');
    }
}
