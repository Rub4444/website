<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BasketController extends Controller
{
    public function basket()
    {
        $orderId = session('orderId');
        $order = $orderId ? Order::find($orderId) : null;

        if (!$order || $order->products->isEmpty()) {
            session()->flash('warning', 'Your basket is empty.');
            return redirect()->route('index'); // Перенаправление, если корзина пуста
        }

        return view('basket', compact('order'));
    }

    public function basketConfirm(Request $request)
    {
        $orderId = session('orderId');
        if (!$orderId) {
            return redirect()->route('index')->with('error', 'Корзина пуста!');
        }

        $order = Order::find($orderId);
        if (!$order || $order->products->isEmpty()) {
            return redirect()->route('index')->with('error', 'Корзина пуста!');
        }

        $success = $order->saveOrder($request->name, $request->phone);

        if ($success) {
            session()->flash('success', 'Ваш заказ успешно оформлен!');
        } else {
            session()->flash('warning', 'Ошибка оформления заказа');
        }
        Order::eraseOrderSum();
        return redirect()->route('index');
    }

    public function basketPlace()
    {
        $orderId = session('orderId');
        if (!$orderId) {
            return redirect()->route('index')->with('error', 'Корзина пуста!');
        }

        $order = Order::find($orderId);
        if (!$order) {
            Log::error('Ошибка при оформлении: заказ не найден');
            return redirect()->route('index')->with('error', 'Ошибка заказа!');
        }

        return view('order', compact('order'));
    }

    public function basketAdd($productId)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('warning', 'Пожалуйста, войдите, чтобы добавить товары в корзину.');
        }

        $orderId = session('orderId');
        if (!$orderId) {
            $order = Order::create(['user_id' => Auth::id()]); // Устанавливаем user_id при создании нового заказа
            session(['orderId' => $order->id]);
        } else {
            $order = Order::find($orderId);
        }

        if (!$order) {
            Log::error('Ошибка добавления товара: заказ не найден');
            return redirect()->route('basket')->with('error', 'Ошибка заказа!');
        }

        $product = Product::find($productId);

        Order::changeFullSum($product->price);

        if (!$product) {
            return redirect()->route('basket')->with('error', 'Товар не найден!');
        }

        if ($order->products->contains($productId)) {
            $pivotRow = $order->products()->where('product_id', $productId)->first()->pivot;
            $pivotRow->count++;
            $pivotRow->update();
        } else {
            $order->products()->attach($productId, ['count' => 1]);
        }

        session()->flash('success', 'Товар "' . $product->name . '" добавлен в корзину');
        return redirect()->route('basket');
    }

    public function basketRemove($productId)
    {
        $orderId = session('orderId');
        $order = $orderId ? Order::find($orderId) : null;

        if (!$order) {
            Log::error('Ошибка удаления товара: заказ не найден');
            return redirect()->route('basket')->with('error', 'Ошибка заказа!');
        }
        $order = Order::find($orderId);
        if ($order->products->contains($productId)) {
            $pivotRow = $order->products()->where('product_id', $productId)->first()->pivot;
            if ($pivotRow->count < 2) {
                $order->products()->detach($productId);
            } else {
                $pivotRow->count--;
                $pivotRow->update();
            }
        }
        $product = Product::find($productId);
        Order::changeFullSum(-$product->price);

        // Удаляем заказ, если в нем нет товаров
        if ($order->products->isEmpty()) {
            $order->delete();
            session()->forget('orderId');
        }

        session()->flash('warning', 'Товар удалён из корзины');
        return redirect()->route('basket');
    }
}
