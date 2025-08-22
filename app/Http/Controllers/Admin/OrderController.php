<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Mail\OrderConfirmed;
use Illuminate\Support\Facades\Mail;

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
        if ($order->status != 1)
        {
            return redirect()->back()->with('error', 'Պատվերը արդեն հաստատված է կամ ավարտված։');
        }

        $order->status = 2; // подтверждён
        $order->save();

        // Получаем email и имя из пользователя, если есть, иначе из заказа
        $email = $order->user->email ?? $order->email;
        $name  = $order->user->name ?? $order->name;

        if ($email)
        {
            Mail::to($email)->send(new OrderConfirmed($name, $order));
        }

        return redirect()->route('home')->with('success', 'Պատվերը հաստատվել է` առաքիչը ճանապարհին է։');
    }


    public function cancel(Request $request, Order $order)
    {
        $request->validate([
            'cancellation_comment' => 'required|string|max:1000',
        ]);

        $order->update([
            'status' => 3, // статус 3 = отменён
            'cancellation_comment' => $request->cancellation_comment,
        ]);

        return redirect()->route('home')->with('success', 'Պատվերը հաջողությամբ չեղարկվել է։');
    }


}
