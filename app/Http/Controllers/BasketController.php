<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Sku;
use App\Models\Coupon;
use App\Http\Requests\AddCouponRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Classes\Basket;
use App\Models\Category;
use App\Services\TelcellService;

class BasketController extends Controller
{
    public function basket()
    {
        $order = (new Basket())->getOrder();
        $categories = Category::all();
        return view('basket', compact('order', 'categories'));
    }

    public function basketConfirm(Request $request, TelcellService $telcell)
    {
        $basket = new Basket();

        // Проверяем купон
        if ($basket->getOrder()->hasCoupon() && !$basket->getOrder()->coupon->availableForUse()) {
            $basket->clearCoupon();
            session()->flash('warning', __('basket.coupon_is_not_available'));
            return redirect()->route('basket');
        }

        $email = Auth::check() ? Auth::user()->email : $request->email;

        $saved = $basket->saveOrder(
            $request->name,
            $request->phone,
            $email,
            $request->delivery_type,
            $request->delivery_city,
            $request->delivery_street,
            $request->delivery_home
        );

        if (!$saved) {
            session()->flash('warning', __('basket.product_is_not_available'));
            return redirect()->route('basket');
        }

        $order = $basket->getOrder();

        session()->flash('success', __('basket.your_order_confirmed'));

        // Создаем счет через Telcell
        $buyer = $request->phone ?: $email;
        $description = "Оплата заказа #{$order->id}";

        $invoiceHtml = $telcell->createInvoiceHtml(
            $buyer,
            $order->getFullSum(),
            $order->id
        );

        return response($invoiceHtml);
    }

    public function basketClear()
    {
        $basket = new Basket();
        $basket->clearBasket(); // метод в классе Basket очищает pivot и session

        session()->flash('success', __('basket.basket_cleared'));
        return redirect()->route('basket');
    }

    public function basketPlace()
    {
        $basket = new Basket();
        $order = $basket->getOrder();

        if (!$basket->countAvailable()) {
            session()->flash('warning', __('basket.product_is_not_available'));
            return redirect()->route('basket');
        }

        $categories = Category::all();
        return view('order', compact('order', 'categories'));
    }

    public function payWithTelcell($orderId)
    {
        $order = Order::findOrFail($orderId);

        $buyer = $order->buyer; // телефон покупателя
        $sum = $order->getFullSum();   // сумма заказа

        $formHtml = app(TelcellService::class)->createInvoiceHtml($buyer, $sum, $orderId);

        return response()->view('telcell.autopost', ['formHtml' => $formHtml]);
    }

    public function basketAdd(Request $request, Sku $sku)
    {
        $quantity = $request->input('quantity', $sku->product->unit === 'kg' ? 0.5 : 1);

        $basket = new Basket();
        $basket->addSku($sku, $quantity);

        return redirect()->route('basket');
    }

    public function basketRemove(Request $request, Sku $sku)
    {
        $quantity = $request->input('quantity', $sku->product->unit === 'kg' ? 0.1 : 1);

        $basket = new Basket();
        $basket->removeSku($sku, $quantity);

        session()->flash('warning', '"' . $sku->product->__('name') . '"' . __('basket.deleted_from_cart'));
        return redirect()->route('basket');
    }

    public function setCoupon(AddCouponRequest $request)
    {
        $basket = new Basket();
        $coupon = Coupon::where('code', $request->coupon)->first();

        if ($coupon && $coupon->availableForUse()) {
            $basket->setCoupon($coupon);
            session()->flash('success', __('basket.coupon_added_to_the_order'));
        } else {
            session()->flash('warning', __('basket.coupon_cannot_be_used'));
        }

        return redirect()->route('basket');
    }

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
            $basket->addSku($sku, $quantity); // можно добавить метод update в Basket при желании
        }

        return response()->json(['success' => true]);
    }
}
