<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Sku;
use App\Models\Coupon;
use App\Http\Requests\AddCouponRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

    public function addAjax(Request $request, Sku $sku)
    {
        $quantity = $request->input(
            'quantity',
            $sku->unit === 'kg' ? 0.5 : 1
        );

        $basket = new \App\Classes\Basket(true);
        $result = $basket->addSku($sku, $quantity);

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Не удалось добавить товар'
            ], 400);
        }

        $order = $basket->getOrder();

        return response()->json([
            'success' => true,
            'message' => 'Товар добавлен в корзину',
            'cart_count' => $order->skus->sum('countInOrder'),
        ]);
    }

    public function updateAjax(Request $request, Sku $sku)
    {
        $delta = (float) $request->delta;

        $basket = new Basket(true);

        if ($delta > 0) {
            $basket->addSku($sku, abs($delta));
        } else {
            $basket->removeSku($sku, abs($delta));
        }

        $order = $basket->getOrder();

        $item = $order->skus->firstWhere('id', $sku->id);

        return response()->json([
            'success' => true,
            'item_count' => $item?->countInOrder ?? 0,
            'cart_count' => $order->skus->sum('countInOrder'),
            'total_sum' => $order->getFullSum(),
        ]);
    }



    public function basketConfirm(Request $request, TelcellService $telcell)
    {
        $basket = new Basket();

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
            $request->delivery_home,
            $request->input('order_note')
        );

        if (!$order) {
            session()->flash('warning', __('basket.product_is_not_available'));
            return redirect()->route('basket');
        }

        $buyer = $request->phone ?: $email;

        Log::info('BasketController before Telcell', ['order_id' => $order->id]);

        $invoiceHtml = $telcell->createInvoiceHtml($order, $buyer);

        Log::info('BasketController after Telcell', [
            'order_id' => $order->id,
            'html_empty' => empty($invoiceHtml),
        ]);

        // ✅ ВАЖНО
        if (empty($invoiceHtml)) {
            return redirect()->route('payment.pending', ['order' => $order->id]);
        }

        return response($invoiceHtml);
    }


    public function basketClear()
    {
        $basket = new Basket();
        // Можно создать метод clear() в Basket, который сбросит session('order')
        session()->forget('order');

        session()->flash('success', __('basket.basket_cleared'));
        return redirect()->route('basket');
    }


    public function basketPlace()
    {
        $basket = new Basket();
        $order = $basket->getOrder();
        if(!$basket->countAvailable())
        {
            session()->flash('warning', __('basket.product_is_not_available'));
            return redirect()->route('basket');
        }
        $categories = Category::all();
        return view('order', compact('order', 'categories'));
    }

    public function payWithTelcell(Order $order)
    {
        return redirect()->route('payment.pending', ['order' => $order->id]);
    }



    public function basketAdd(Request $request, Sku $skus)
{
    $quantity = $request->input('quantity', $skus->unit === 'kg' ? 0.5 : 1);

    $result = (new Basket(true))->addSku($skus, $quantity);

    // if($result)
    // {
    //     session()->flash(
    //         'success',
    //         __('basket.Product') . ' "' . $skus->product->__('name') . '" ' . __('basket.added_to_cart')
    //     );
    // }
    // else
    // {
    //     session()->flash('warning', __('basket.Product') . '"' . $skus->product->__('name') . '" '. __('basket.not_added_to_cart'));
    // }

    return redirect()->route('basket');
}


    public function basketRemove(Request $request, Sku $skus)
{
    $quantity = $request->input('quantity', $skus->unit === 'kg' ? 0.1 : 1);

    (new Basket())->removeSku($skus, $quantity);

    session()->flash('warning','"' . $skus->product->__('name') . '"' . __('basket.deleted_from_cart'));
    return redirect()->route('basket');
}

    public function setCoupon(AddCouponRequest $request)
    {
        $coupon = Coupon::where('code', $request->coupon)->first();
        if($coupon->availableForUse())
        {
            (new Basket())->setCoupon($coupon);
            session()->flash('success', __('basket.coupon_added_to_the_order'));
        }
        else
        {
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

    if ($quantity == 0) {
        $this->basket->remove($sku);
    } else {
        $this->basket->update($sku, $quantity);
    }

    return response()->json(['success' => true]);
}

}
