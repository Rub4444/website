<?php

namespace App\Http\Controllers;

use App\Models\Order;
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

    public function basketAdd(Product $product)
    {
        $result = (new Basket(true))->addProduct($product);
        if($result)
        {
            session()->flash('success', 'Товар "' . $product->name . '" добавлен в корзину');
        }
        else
        {
            session()->flash('warning', 'Товар "' . $product->name . '" не добавлен в корзину');
        }
        return redirect()->route('basket');
    }

    public function basketRemove(Product $product)
    {
        (new Basket())->removeProduct($product);

        session()->flash('warning', 'Товар "' . $product->name . '" удалён из корзины');
        return redirect()->route('basket');
    }
}
