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

    // private function performCancel(Order $order, ?string $comment = null)
    // {
    //     foreach ($order->skus as $sku) {
    //         $sku->count += $sku->pivot->count;
    //         $sku->save();
    //     }

    //     $order->markAsCancelled();
    //     $order->cancellation_comment = $comment;
    //     $order->save();
    // }
    // Админ
    public function cancel(Request $request, Order $order)
    {
        $request->validate(['cancellation_comment' => 'nullable|string|max:1000']);
        $this->performCancel($order, $request->cancellation_comment);

        // email уведомление
        $email = $order->user->email ?? $order->email;
        if ($email) Mail::to($email)->send(new OrderCancelled($order, $request->cancellation_comment));

        return redirect()->route('home')->with('success', 'Պատվերը հաջողությամբ չեղարկվել է');
    }

    // Пользователь
//     public function cancelOrder(Request $request, Order $order)
// {
//     $this->authorize('cancel', $order);

//     \Log::info('Cancel order start', [
//         'order_id' => $order->id,
//         'status' => $order->status
//     ]);

//     $telcell = new \App\Services\TelcellService();
//     $response = $telcell->cancelOrder($order);

//     \Log::info('Telcell cancelOrder response', [
//         'order_id' => $order->id,
//         'response' => $response
//     ]);

//     if (is_array($response) && isset($response['invoice'])) {
//         $this->performCancel($order, $request->cancellation_comment ?? null);

//         \Log::info('Order successfully cancelled', [
//             'order_id' => $order->id,
//             'new_status' => $order->status
//         ]);

//         return back()->with('success', 'Заказ успешно отменён.');
//     }

//     $message = is_array($response) ? ($response['message'] ?? 'Неизвестная ошибка') : 'Ошибка связи с Telcell';

//     \Log::error('Failed to cancel order', [
//         'order_id' => $order->id,
//         'response' => $response,
//         'message' => $message
//     ]);

//     return back()->with('error', 'Не удалось отменить заказ: ' . $message);
// }
public function cancelOrder(Request $request, Order $order)
{
    $this->authorize('cancel', $order);

    \Log::info('Cancel order start', [
        'order_id' => $order->id,
        'status' => $order->status,
        'invoice_id' => $order->invoice_id ?? null
    ]);

    // Проверяем наличие invoice_id
    if (!$order->invoice_id) {
        \Log::error('CancelOrder: invoice_id missing', [
            'order_id' => $order->id
        ]);
        return back()->with('error', 'Невозможно отменить заказ: invoice_id отсутствует.');
    }

    $telcell = new \App\Services\TelcellService();
    $response = $telcell->cancelOrder($order);

    \Log::info('Telcell cancelOrder response', [
        'order_id' => $order->id,
        'response' => $response
    ]);

    if (is_array($response) && ($response['status'] ?? null) === 'OK') {
        $this->performCancel($order, $request->cancellation_comment ?? null);

        \Log::info('Order successfully cancelled', [
            'order_id' => $order->id,
            'new_status' => $order->status
        ]);

        return back()->with('success', 'Заказ успешно отменён.');
    }

    $message = is_array($response) ? ($response['message'] ?? 'Неизвестная ошибка') : 'Ошибка связи с Telcell';

    \Log::error('Failed to cancel order', [
        'order_id' => $order->id,
        'response' => $response,
        'message' => $message
    ]);

    return back()->with('error', 'Не удалось отменить заказ: ' . $message);
}

protected function performCancel(Order $order, ?string $comment = null)
{
    $order->status = 0; // отменён
    $order->cancellation_comment = $comment;
    $order->save();
}




    // public function cancel(Request $request, Order $order)
    // {
    //     $request->validate([
    //         'cancellation_comment' => 'nullable|string|max:1000',
    //     ]);

    //     // Восстанавливаем товары на склад
    //     foreach($order->skus as $sku)
    //     {
    //         $sku->count += $sku->pivot->count;
    //         $sku->save();
    //     }

    //     $order->markAsCancelled(); // вместо $order->status = 3
    //     $order->cancellation_comment = $request->cancellation_comment;
    //     $order->save();

    //     // Отправка email
    //     $email = $order->user->email ?? $order->email;
    //     if ($email) {
    //         Mail::to($email)->send(new OrderCancelled($order, $request->cancellation_comment));
    //     }

    //     return redirect()->route('home')->with('success', 'Պատվերը հաջողությամբ չեղարկվել է');
    // }

    // public function cancelOrder(Request $request, Order $order)
    // {
    //     $this->authorize('cancel', $order); // проверка через Policy

    //     $telcell = new \App\Services\TelcellService();

    //     $response = $telcell->cancelBill($order);

    //     if (is_array($response) && ($response['status'] ?? null) === 'OK') {
    //         $order->markAsCancelled();
    //         $order->cancellation_comment = $request->cancellation_comment ?? null;
    //         $order->save();

    //         return back()->with('success', 'Заказ успешно отменён.');
    //     }

    //     $message = is_array($response) ? ($response['message'] ?? 'Неизвестная ошибка') : 'Ошибка связи с Telcell';
    //     return back()->with('error', 'Не удалось отменить заказ: ' . $message);
    // }


    // public function handleReturn(Request $request)
    // {
    //     $orderId = $request->query('order'); // получаем ?order=40
    //     if (!$orderId) {
    //         return redirect('/')->with('error', 'Не указан номер заказа.');
    //     }

    //     $order = Order::find($orderId);
    //     if (!$order) {
    //         return redirect('/')->with('error', 'Заказ не найден.');
    //     }

    //     // Если статус оплаты в базе уже обновился после callback-а
    //     if ($order->status == 2) {
    //         return view('payments.success', compact('order'));
    //     } else {
    //         return view('payments.fail', compact('order'));
    //     }
    // }

}
