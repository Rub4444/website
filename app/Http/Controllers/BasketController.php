<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Sku;
use App\Models\Coupon;
use App\Http\Requests\AddCouponRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Classes\Basket;
use App\Models\Category;
use App\Services\TelcellService;

class BasketController extends Controller
{
    // Просмотр корзины
    public function basket()
    {
        $basket = new Basket();
        $categories = Category::all();
        $order = $basket->getOrder();
        $skus = $basket->getSkus();

        return view('basket', compact('order', 'skus', 'categories'));
    }

    // Подтверждение заказа
    public function basketConfirm(Request $request, TelcellService $telcell)
    {
        $basket = new Basket();

        // Проверка купона
        if ($basket->getOrder()->hasCoupon() && !$basket->getOrder()->coupon->availableForUse()) {
            $basket->clearCoupon();
            session()->flash('warning', __('basket.coupon_is_not_available'));
            return redirect()->route('basket');
        }

        $email = Auth::check() ? Auth::user()->email : $request->email;

        $order = $basket->saveOrder(
            $request->name,
            $request->phone,
            $email,
            $request->delivery_type,
            $request->delivery_city,
            $request->delivery_street,
            $request->delivery_home
        );

        if (!$order) {
            session()->flash('warning', __('basket.product_is_not_available'));
            return redirect()->route('basket');
        }

        session()->flash('success', __('basket.your_order_confirmed'));

        // Создаем счёт через Telcell
        $buyer = $request->phone ?: $email;
        $description = "Оплата заказа #{$order->id}";
        $invoiceHtml = $telcell->createInvoiceHtml($buyer, $order->sum, $order->id);

        return response($invoiceHtml);
    }

    // Очистка корзины
    public function basketClear()
    {
        $basket = new Basket();
        $basket->clearBasket();

        session()->flash('success', __('basket.basket_cleared'));
        return redirect()->route('basket');
    }

    // Страница оформления заказа
    public function basketPlace()
    {
        $basket = new Basket();
        if (!$basket->countAvailable()) {
            session()->flash('warning', __('basket.product_is_not_available'));
            return redirect()->route('basket');
        }

        $order = $basket->getOrder();
        $skus = $basket->getSkus();
        $categories = Category::all();

        return view('order', compact('order', 'skus', 'categories'));
    }

    // Платеж через Telcell
    public function payWithTelcell($orderId)
    {
        $order = Order::findOrFail($orderId);
        $buyer = $order->buyer ?? $order->phone;
        $sum = $order->sum;

        $formHtml = app(TelcellService::class)->createInvoiceHtml($buyer, $sum, $orderId);

        return response()->view('telcell.autopost', ['formHtml' => $formHtml]);
    }

    // Добавление товара в корзину
    public function basketAdd(Request $request, Sku $sku = null)
    {
        if (!$sku) {
            session()->flash('warning', __('basket.product_not_found'));
            return redirect()->route('basket');
        }

        $quantity = $request->input('quantity', $sku->unit === 'kg' ? 0.5 : 1);
        $basket = new Basket(true);
        $basket->addSku($sku, $quantity);

        return redirect()->route('basket');
    }

    // Удаление товара из корзины
    public function basketRemove(Request $request, Sku $sku = null)
    {
        if (!$sku) {
            session()->flash('warning', __('basket.product_not_found'));
            return redirect()->route('basket');
        }

        $quantity = $request->input('quantity', $sku->unit === 'kg' ? 0.1 : 1);
        $basket = new Basket();
        $basket->removeSku($sku, $quantity);

        session()->flash('warning','"' . ($sku->product->__('name') ?? 'Unknown') . '"' . __('basket.deleted_from_cart'));
        return redirect()->route('basket');
    }

    // Применение купона
    public function setCoupon(AddCouponRequest $request)
    {
        $coupon = Coupon::where('code', $request->coupon)->first();
        $basket = new Basket();

        if ($coupon && $coupon->availableForUse()) {
            $basket->setCoupon($coupon);
            session()->flash('success', __('basket.coupon_added_to_the_order'));
        } else {
            session()->flash('warning', __('basket.coupon_cannot_be_used'));
        }

        return redirect()->route('basket');
    }

    // Обновление количества товара через AJAX
    public function update(Request $request, Sku $sku)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:0',
        ]);

        $quantity = $request->input('quantity');
        $basket = new Basket();

        if ($quantity == 0) {
            $basket->removeSku($sku);
        } else {
            // Устанавливаем точное количество
            $current = $basket->getSkus()->firstWhere('id', $sku->id);
            if ($current) {
                $current->countInOrder = $quantity;
                session(['basket_skus' => $basket->getSkus()]);
            } else {
                $basket->addSku($sku, $quantity);
            }
        }

        return response()->json(['success' => true]);
    }
}
