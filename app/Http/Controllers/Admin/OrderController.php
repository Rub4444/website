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
        if (!$order->isStatus(Order::STATUS_PENDING)) {
            return redirect()->back()->with('error', 'Պատվերը արդեն հաստատված է կամ ավարտված։');
        }

        $order->markAsShipped(); // вместо $order->status = 5
        // Берём email из пользователя, если есть, иначе из заказа
        $email = $order->user->email ?? $order->email;
        $name  = $order->user->name ?? $order->name;

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


    // public function cancel(Request $request, Order $order)
    // {
    //     $request->validate([
    //         'cancellation_comment' => 'required|string|max:1000',
    //     ]);

    //     // Восстанавливаем товары на склад
    //     foreach($order->skus as $sku)
    //     {
    //         $sku->count += $sku->pivot->count;
    //         $sku->save();
    //     }

    //     $order->update([
    //         'status' => 3,
    //         'cancellation_comment' => $request->cancellation_comment,
    //     ]);

    //     // 2. Отправляем email
    //     $email = $order->user->email ?? $order->email;
    //     if ($email) {
    //         Mail::to($email)->send(new OrderCancelled($order, $request->cancellation_comment));
    //     }

    //     return redirect()->route('home')->with('success', 'Պատվերը հաջողությամբ չեղարկվել է');
    // }
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

    //tellcelll
    public function cancelOrder(Request $request, Order $order)
    {
        $this->authorize('cancel', $order); // проверка через Policy

        $telcell = new \App\Services\TelcellService();

        $response = $telcell->cancelBill($order);

        if($response['status'] == 'OK') {
            $order->status = 3; // Отменён
            $order->cancellation_comment = $request->cancellation_comment ?? null;
            $order->save();

            return back()->with('success', 'Заказ успешно отменён.');
        }

        return back()->with('error', 'Не удалось отменить заказ: ' . $response['message']);
    }

    public function refundOrder(Request $request, Order $order)
    {
        $this->authorize('refund', $order); // проверка через Policy

        $request->validate([
            'refund_sum' => 'required|numeric|min:1|max:' . $order->paid_amount,
        ]);

        $telcell = new \App\Services\TelcellService();
        $response = $telcell->refundBill($order, $request->refund_sum);

        if($response['status'] == 'OK') {
            $order->paid_amount -= $request->refund_sum; // уменьшаем оплаченный остаток
            $order->save();

            return back()->with('success', 'Частичный возврат выполнен.');
        }

        return back()->with('error', 'Не удалось выполнить возврат: ' . $response['message']);
    }


}
