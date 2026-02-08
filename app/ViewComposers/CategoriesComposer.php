<?php

namespace App\ViewComposers;

use Illuminate\View\View;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoriesComposer
{
    public function compose(View $view)
    {
        $categories = Cache::remember('view_categories', 3600, function () {
            return Category::orderBy('name')->get();
        });
        $view->with('categories', $categories);
    }
}
