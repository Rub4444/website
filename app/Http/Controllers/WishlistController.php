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

    $categoryIds = $skus->pluck('product.category.id')->unique()->filter()->values();
    $wishlistSkuIds = $skus->pluck('id')->toArray();

    // Один запрос: кандидаты из категорий wishlist, затем до 2 SKU на категорию в PHP
    $recommendedSkus = collect();
    if ($categoryIds->isNotEmpty() && count($wishlistSkuIds) > 0) {
        $limit = min(50, $categoryIds->count() * 2 + 10);
        $candidates = Sku::with('product.category')
            ->whereHas('product', function ($query) use ($categoryIds) {
                $query->whereIn('category_id', $categoryIds);
            })
            ->whereNotIn('id', $wishlistSkuIds)
            ->whereNull('deleted_at')
            ->inRandomOrder()
            ->limit($limit)
            ->get();
        foreach ($categoryIds as $categoryId) {
            $byCategory = $candidates->filter(fn ($s) => $s->product && $s->product->category && (int) $s->product->category->id === (int) $categoryId)->take(2);
            $recommendedSkus = $recommendedSkus->merge($byCategory);
        }
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
