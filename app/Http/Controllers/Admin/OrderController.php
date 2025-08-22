<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Mail\OrderConfirmed;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderCancelled;
use App\Classes\Basket;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('status', '!=', 0)
                        ->orderBy('created_at', 'desc') // <-- добавлено
                        ->paginate(10);
        return view('auth.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $skus = $order->skus()->withTrashed()->get();
        return view('auth.orders.show', compact('order', 'skus'));
    }

    public function confirm(Order $order)
{
    if ($order->status != 1) {
        return redirect()->back()->with('error', 'Պատվերը արդեն հաստատված է կամ ավարտված։');
    }

    $order->status = 2;
    $order->save();

    // Берём email из пользователя, если есть, иначе из заказа
    $email = $order->user->email ?? $order->email;
    $name  = $order->user->name ?? $order->name;

    // Проверяем, что это валидный email
    if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        Mail::to($email)->send(new OrderConfirmed($name, $order));
    }

    return redirect()->route('home')->with('success', 'Պատվերը հաստատվել է` առաքիչը ճանապարհին է։');
}



    // public function cancel(Request $request, Order $order)
    // {
    //     $request->validate([
    //         'cancellation_comment' => 'required|string|max:1000',
    //     ]);

    //     $order->update([
    //         'status' => 3, // статус 3 = отменён
    //         'cancellation_comment' => $request->cancellation_comment,
    //     ]);

    //     return redirect()->route('home')->with('success', 'Պատվերը հաջողությամբ չեղարկվել է։');
    // }


    // public function cancel(Request $request, Order $order)
    // {
    //     $request->validate([
    //         'cancellation_comment' => 'required|string|max:1000',
    //     ]);

    //     // 1️⃣ Меняем статус заказа и сохраняем причину отмены
    //     $order->update([
    //         'status' => 3, // отменён
    //         'cancellation_comment' => $request->cancellation_comment,
    //     ]);

    //     // 2️⃣ Возвращаем товары обратно в корзину
    //     $basket = new Basket(true); // создаем корзину, если нет
    //     foreach ($order->skus as $sku) {
    //         $basket->addSku($sku, $sku->pivot->count); // добавляем все позиции обратно
    //     }

    //     // 3️⃣ Отправляем уведомление клиенту
    //     $email = $order->user->email ?? $order->email;
    //     if ($email) {
    //         Mail::to($email)->send(new OrderCancelled($order, $request->cancellation_comment));

    //     }

    //     return redirect()->route('home')->with('success', 'Заказ отменен, товары возвращены в корзину, клиент уведомлен.');
    // }


public function cancel(Request $request, Order $order)
{
    $request->validate([
        'cancellation_comment' => 'required|string|max:1000',
    ]);

    $order->update([
        'status' => 3,
        'cancellation_comment' => $request->cancellation_comment,
    ]);

    // 1. Создаем корзину для пользователя заказа
    if ($order->user_id) {
        $basket = new Basket(false); // false — не создаем новую сессию
        $basket->setUserId($order->user_id); // нужно добавить метод setUserId в Basket

        foreach ($order->skus as $sku) {
            $basket->addSku($sku, $sku->pivot->count);
        }
    }

    // 2. Отправляем email
    $email = $order->user->email ?? $order->email;
    if ($email) {
        Mail::to($email)->send(new OrderCancelled($order, $request->cancellation_comment));
    }

    return redirect()->route('home')->with('success', 'Պատվերը հաջողությամբ չեղարկվել է և товары возвращены в корзину пользователя.');
}

}
