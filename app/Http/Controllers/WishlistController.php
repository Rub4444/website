<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Sku;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
   public function index()
{
    $user = Auth::user();

    // Загружаем wishlist (включая мягко удалённые)
    $skus = $user->wishlist()
        ->with([
            'product' => function ($q) {
                $q->withTrashed();
            },
            'product.category',
            'propertyOptions.property'
        ])
        ->withTrashed()
        ->get();

    // Категории товаров из wishlist
    $categoryIds = $skus->pluck('product.category.id')->unique()->filter();

    // ID SKU из wishlist, чтобы исключить их из рекомендаций
    $wishlistSkuIds = $skus->pluck('id')->toArray();

    // Соберём рекомендации по 2 товара из каждой категории
    $recommendedSkus = collect();

    foreach ($categoryIds as $categoryId) {
        $skusInCategory = \App\Models\Sku::with('product.category')
            ->whereHas('product', function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->whereNotIn('id', $wishlistSkuIds)
            ->whereNull('deleted_at')
            ->inRandomOrder()
            ->take(2)
            ->get();

        $recommendedSkus = $recommendedSkus->merge($skusInCategory);
    }

    return view('wishlist.index', compact('skus', 'recommendedSkus'));
}



    public function toggle($skuId)
    {
        $user = Auth::user();
        $existing = $user->wishlist()->where('sku_id', $skuId)->first();

        if ($existing)
        {
            $existing->pivot->delete();
            return response()->json(['status' => 'removed']);
        }
        else
        {
            Wishlist::create([
                'user_id' => $user->id,
                'sku_id' => $skuId,
            ]);
            return response()->json(['status' => 'added']);
        }
    }

}
