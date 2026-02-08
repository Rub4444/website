<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\Order;

class MergeBasketAfterLogin
{
    public function handle(Login $event)
    {
        $user = $event->user;

        /** @var Order|null $sessionOrder */
        $sessionOrder = session('order');

        if (!$sessionOrder || $sessionOrder->skus->isEmpty()) {
            return;
        }

        // Уже залогиненный заказ в сессии — не мержить повторно (иначе количества удваиваются)
        if ($sessionOrder->getKey() && (int) $sessionOrder->user_id === (int) $user->id) {
            $sessionOrder->load('skus');
            session(['order' => $sessionOrder]);
            return;
        }

        // активная корзина пользователя
        $dbOrder = Order::where('user_id', $user->id)
            ->where('status', Order::STATUS_PENDING)
            ->with('skus')
            ->first();

        // если в БД корзины нет — просто привязываем session-корзину
        if (!$dbOrder) {
            $sessionOrder->user_id = $user->id;
            $sessionOrder->save();

            session(['order' => $sessionOrder]);
            return;
        }

        // объединяем корзины ЧЕРЕЗ pivot
        foreach ($sessionOrder->skus as $sku) {
            $existing = $dbOrder->skus->firstWhere('id', $sku->id);

            if ($existing) {
                $dbOrder->skus()->updateExistingPivot(
                    $sku->id,
                    [
                        'count' => $existing->pivot->count + $sku->countInOrder,
                        'price' => $sku->price,
                    ]
                );
            } else {
                $dbOrder->skus()->attach(
                    $sku->id,
                    ['count' => $sku->countInOrder, 'price' => $sku->price]
                );
            }
        }

        // перезагружаем связь после изменений
        $dbOrder->load('skus');

        session(['order' => $dbOrder]);
    }
}
