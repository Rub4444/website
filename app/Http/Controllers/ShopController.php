<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sku;
use App\Models\Category;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Sku::query();

        if ($request->filled('category')) {
                        $query->whereHas('product', function ($q) use ($request) {
                $q->where('category_id', $request->category);
            });

        }

        if ($request->filled('sort')) {
        if ($request->sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        }
    }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Здесь используем paginate, чтобы можно было применить withQueryString()
        $skus = $query->paginate(60)->withQueryString(); // ✅ Пагинация + фильтры

        $categories = Category::all();

        return view('shop.index', compact('skus', 'categories'));
    }
}
