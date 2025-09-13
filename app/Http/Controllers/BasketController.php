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

    public function basketConfirm(Request $request, TelcellService $telcell)
    {
        $basket = new Basket();

        if ($basket->getOrder()->hasCoupon() && !$basket->getOrder()->coupon->availableForUse())
        {
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

        // // Сохраняем заметку
        // $order->note = $request->input('order_note', null);
        // $order->save();

        if ($order === true)
        {
            $order = \App\Models\Order::latest()->first();
        }
        // \Log::info('ORDER ID:', ['order_id' => $order->id]);


        if (!$order)
        {
            session()->flash('warning', __('basket.product_is_not_available'));
            return redirect()->route('basket');
        }

        // Создаем счёт через Telcell
        $buyer = $request->phone ?: $email;
        $description = "Оплата заказа #{$order->id}";
        // $issuerId = (string)$order->id;
        Log::info("BasketController->basketConfirm");
        $result = $telcell->createInvoice(
            $buyer,         // string
            $order->sum,    // float
            $order->id,     // int — ID заказа
            1,              // valid_days
            $description    // строка описания (опционально)
        );
        $invoiceHtml = $telcell->createInvoiceHtml(
            $buyer,
            $order->sum,
            $order->id
        );
        Log::info("BasketController->basketConfirm after createInvoice");

        session()->flash('success', __('basket.your_order_confirmed'));

        // if (isset($result['invoice']))
        // {
        //     // Редирект на страницу оплаты Telcell
        //     $paymentUrl = "https://telcellmoney.am/payments/invoice/?invoice={$result['invoice']}&return_url=" . route('payment.return');
        //     return redirect()->away($paymentUrl);
        // }
        Log::info("BasketController->basketConfirm after order confirmed");

        // session()->flash('warning', 'Ошибка при создании платежа Telcell.');
        return response($invoiceHtml);

        // return redirect()->route('index');
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

    public function payWithTelcell($orderId)
    {
        $order = Order::findOrFail($orderId);

        $buyer = $order->buyer; // телефон покупателя
        $sum = $order->total;   // сумма заказа

        // Генерируем HTML форму Telcell
        $formHtml = app(TelcellService::class)->createInvoiceHtml($buyer, $sum, $orderId);

        // Возвращаем view, которая сразу отправляет форму
        return response()->view('telcell.autopost', ['formHtml' => $formHtml]);
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
