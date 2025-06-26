<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Currency;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\Sku;
use App\Http\Requests\ProductFilterRequest;
use App\Http\Requests\SubscriptionRequest;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use DebugBar\DebugBar;


class MainController extends Controller
{
    public function index(ProductFilterRequest $request)
{
    // Получаем ID SKU с минимальной ценой на каждый product_id
    $minPriceSkuIds = Sku::selectRaw('MIN(price) as min_price, product_id')
    ->whereHas('product.category')
    ->groupBy('product_id')
    ->get()
    ->map(function ($item) {
        return Sku::where('product_id', $item->product_id)
                ->where('price', $item->min_price)
                ->orderBy('id')
                ->value('id');
    })->filter();


    // Загружаем только эти SKUs
    $skus = Sku::with(['product', 'product.category'])
        ->whereIn('id', $minPriceSkuIds)
        ->paginate(8)
        ->withPath("?" . $request->getQueryString());

    return view('index', compact('skus'));
}


    public function categories()
    {
        return view('categories');
    }

    public function category($code)
    {
        $category = Category::where('code', $code)->firstOrFail();
        $categories = Category::all();
        return view('category', compact('category', 'categories'));
    }

    public function sku($categoryCode, $productCode, Sku $skus)
    {
        if($skus->product->code != $productCode)
        {
            abort(404, 'Product not found');
        }

        if($skus->product->category->code != $categoryCode)
        {
            abort(404, 'Category not found');
        }

        $relatedSkus = $skus->relatedSkus();

        return view('product', compact('skus', 'relatedSkus'));
    }

    public function subscribe(Sku $sku, SubscriptionRequest $request)
    {
        Subscription::create([
            'email' => $request->email,
            'sku_id' => $sku->id,
        ]);

        return redirect()->back()->with('success', 'Շնորհակալություն, ապրանքի առկայության դեպքում մենք կտեղեկացնենք Ձեզ');
    }

    public function changeLocale($locale)
    {
        $availableLocales = ['arm', 'en'];
        if(!in_array($locale, $availableLocales))
        {
            $locale = config('app.locale');
        }
        session(['locale' => $locale]);
        App::setLocale($locale);
        return redirect()->back();
    }

    public function changeCurrency($currencyCode)
    {
        $currency = Currency::byCode($currencyCode)->firstOrFail();
        session(['currency' => $currency->code]);
        return redirect()->back();
    }

    public function howToUse()
    {
        return view('how-to-use');
    }

    public function offer()
    {
        return view('offer');
    }

    public function delivery()
    {
        return view('delivery');
    }

    public function privacy()
    {
        return view('privacy');
    }

    public function about()
    {
        return view('about-us');
    }
}
