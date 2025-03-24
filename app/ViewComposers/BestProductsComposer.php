<?php

namespace App\ViewComposers;
use App\Models\Order;
use App\Models\Product;
use Illuminate\View\View;
use App\Models\Sku;

class BestProductsComposer
{
    public function compose(View $view)
    {
        $bestSkuIds = Order::get()->map->skus->flatten()->map->pivot->mapToGroups(function ($pivot)
        {
            return [$pivot->sku_id => $pivot->count];
        })->map->sum()->sortByDesc(null)->take(3)->keys()->toArray();

        $bestSkus = Sku::whereIn('id', $bestSkuIds)->get();
        $view->with('bestSkus', $bestSkus);
    }
}
