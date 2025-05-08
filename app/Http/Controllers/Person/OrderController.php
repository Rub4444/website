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
        // Проверка, что заказ принадлежит текущему пользователю
        if ($order->user_id !== Auth::id()) {
            abort(403); // Если заказ не принадлежит пользователю, показываем ошибку
        }

        // Получаем SKUs и их связанные данные (count и price)
        $skus = $order->skus; // Теперь у вас есть доступ к count и price через pivot

        // Получаем координаты для карты (например, из данных заказа)
        $latitude = $order->latitude;  // Предположим, что эти поля существуют в заказе
        $longitude = $order->longitude;

        return view('auth.orders.show', compact('order', 'skus'));
    }


}
