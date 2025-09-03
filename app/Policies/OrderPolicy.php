<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;

class OrderPolicy
{
    /**
     * Проверка, может ли пользователь отменить заказ
     */
    public function cancel(User $user, Order $order)
    {
        // Только владелец заказа или админ
        return $user->id === $order->user_id || $user->isAdmin();
    }

    /**
     * Проверка, может ли пользователь сделать частичный возврат
     */
    public function refund(User $user, Order $order)
    {
        // Только админ
        return $user->isAdmin();
    }
}
