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
        $order->markAsShipped(); // вместо $order->status = 5

        // Берём email из пользователя, если есть, иначе из заказа
        $email = $order->user->email ?? $order->email;
        $name  = $order->user->name ?? $order->name;

        if ($email && filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            Mail::to($email)->send(new OrderConfirmed($name, $order));
        }

        return redirect()->route('home')->with('success', 'Պատվերը հաստատվել է` առաքիչը ճանապարհին է։');
    }

    public function cancel(Request $request, Order $order)
    {
        $request->validate([
            'cancellation_comment' => 'nullable|string|max:1000',
        ]);

        // Восстанавливаем товары на склад
        foreach($order->skus as $sku)
        {
            $sku->count += $sku->pivot->count;
            $sku->save();
        }

        $order->markAsCancelled(); // вместо $order->status = 3
        $order->cancellation_comment = $request->cancellation_comment;
        $order->save();

        // Отправка email
        $email = $order->user->email ?? $order->email;
        if ($email) {
            Mail::to($email)->send(new OrderCancelled($order, $request->cancellation_comment));
        }

        return redirect()->route('home')->with('success', 'Պատվերը հաջողությամբ չեղարկվել է');
    }
}
