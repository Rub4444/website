<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subscription;
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
        $productsQuery = Product::with('category');

        if ($request->filled('price_from'))
        {
            $productsQuery->where('price', '>=', $request->price_from);
        }
        if ($request->filled('price_to'))
        {
            $productsQuery->where('price', '<=', $request->price_to);
        }
        foreach (['hit', 'new', 'recommend'] as $field)
        {
            if ($request->has($field))
            {
                $productsQuery->$field();
            }
        }
        $products = $productsQuery->paginate(6)->withPath("?" . $request->getQueryString());
        $categories = Category::all();
        return view('index', compact('products', 'categories'));
    }

    public function categories()
    {
        $categories = Category::get();
        return view('categories', compact('categories'));
    }

    public function category($code)
    {
        $category = Category::where('code', $code)->firstOrFail();
        $categories = Category::all();
        return view('category', compact('category', 'categories'));
    }

    public function product($category, $productCode)
    {
        $product = Product::withTrashed()->byCode($productCode)->firstOrFail();
        $categories = Category::all();
        return view('product', compact('product', 'categories'));
    }
    public function subscribe(SubscriptionRequest $request, Product $product)
    {
        Subscription::create([
            'email' => $request->email,
            'product_id' => $product->id,
        ]);
        return redirect()->back()->with('success', 'Շնորհակալություն, ապրանքի առկայության դեպքում մենք կտեղեկացնենք Ձեզ');
    }
    public function changeLocale($locale)
    {
        $availableLocales = ['ru', 'en'];
        if(!in_array($locale, $availableLocales))
        {
            $locale = config('app.locale');
        }
        session(['locale' => $locale]);
        App::setLocale($locale);
        return redirect()->back();
    }
}
