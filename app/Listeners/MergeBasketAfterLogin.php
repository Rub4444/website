<?php
namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\Order;
use App\Classes\Basket;

class MergeBasketAfterLogin
{
    public function handle(Login $event)
    {
        $user = $event->user;

        // корзина из session
        $sessionOrder = session('order');

        if (!$sessionOrder || $sessionOrder->skus->isEmpty()) {
            return;
        }

        // активный заказ пользователя из БД
        $dbOrder = Order::where('user_id', $user->id)
            ->where('status', Order::STATUS_PENDING)
            ->first();

        // если в БД нет корзины — просто привязываем session
        if (!$dbOrder) {
            $sessionOrder->user_id = $user->id;
            session(['order' => $sessionOrder]);
            return;
        }

        // объединяем товары
        foreach ($sessionOrder->skus as $sku) {
            $existing = $dbOrder->skus->firstWhere('id', $sku->id);

            if ($existing) {
                $existing->countInOrder += $sku->countInOrder;
            } else {
                $dbOrder->skus->push($sku);
            }
        }

        session(['order' => $dbOrder]);
    }
}
