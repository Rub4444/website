<?php

namespace App\Http\Controllers\Person;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()->active()->paginate(10);

        // Возвращаем вид с заказами текущего пользователя
        return view('auth.orders.index', compact('orders'));

    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['skus.product', 'currency', 'coupon']);
        $skus = $order->skus;

        $latitude = $order->latitude;
        $longitude = $order->longitude;

        return view('auth.orders.show', compact('order', 'skus'));
    }


}
