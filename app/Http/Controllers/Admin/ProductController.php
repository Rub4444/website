<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProductRequest;
use App\Models\Property;

class ProductController extends Controller
{
    // public function index(Request $request)
    // {
    //     $search = $request->input('search');

    //     $query = Product::query();

    //     if ($search)
    //     {
    //         $query->where('name', 'like', '%' . $search . '%');
    //     }

    //     $products = $query->paginate(50);

    //     // Чтобы пагинация сохраняла параметр поиска в ссылках
    //     $products->appends(['search' => $search]);

    //     return view('auth.products.index', compact('products', 'search'));
    // }


//     public function index(Request $request)
// {
//     $search = $request->input('search');

//     $query = Product::query();

//     if ($search)
//     {
//         $query->where('name', 'like', '%' . $search . '%');
//     }

//     // Добавляем сортировку по дате создания (новые первыми)
//     $query->orderBy('created_at', 'desc');

//     $products = $query->paginate(50);

//     // Сохраняем параметр поиска в ссылках пагинации
//     $products->appends(['search' => $search]);

//     return view('auth.products.index', compact('products', 'search'));
// }
    public function index(Request $request)
{
    $search = $request->input('search');

    $query = Product::query();

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhereHas('skus', function ($skuQuery) use ($search) {
                  $skuQuery->where('name', 'like', '%' . $search . '%');
              });
        });
    }

    // Новые продукты первыми
    $query->orderBy('created_at', 'desc');

    $products = $query->paginate(50);

    $products->appends(['search' => $search]);

    return view('auth.products.index', compact('products', 'search'));
}

    public function tree(Request $request)
    {
        $search = $request->input('search');

        $categories = Category::with(['products' => function($query) use ($search) {
            if ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            }
        }])->get();

        return view('auth.products.tree', compact('categories', 'search'));
    }


    public function create()
    {
        $categories = Category::get();
        $properties = Property::get();
        return view('auth.products.form', compact('categories', 'properties'));
    }

    // public function store(ProductRequest $request)
    // {
    //     $params = $request->all();

    //     // Убедимся, что чекбоксы не остаются NULL
    //     foreach (['new', 'hit', 'recommend'] as $field)
    //     {
    //         $params[$field] = $request->has($field) ? 1 : 0;
    //     }

    //     Product::create($params);
    //     return redirect()->route('products.index')->with('success', 'Продукт добавлен.');
    // }
    public function store(ProductRequest $request)
    {
        $params = $request->all();

        foreach (['new', 'hit', 'recommend'] as $field) {
            $params[$field] = $request->has($field) ? 1 : 0;
        }

        $product = Product::create($params);

        // 💡 Добавим привязку свойств
        if ($request->has('property_id')) {
            $product->properties()->sync($request->property_id);
        }

        return redirect()->route('products.index')->with('success', 'Продукт добавлен.');
    }


    public function show(Product $product)
    {
        return view('auth.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::get();
        $properties = Property::get();
        return view('auth.products.form', compact('product', 'categories', 'properties'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $params = $request->all();

        // Удаляем старое изображение, если загружено новое
        // if ($request->hasFile('image'))
        // {
        //     if ($product->image)
        //     {
        //         Storage::disk('public')->delete($product->image);
        //     }
        //     $params['image'] = $request->file('image')->store('products', 'public');
        // }

        // Убедимся, что чекбоксы не остаются NULL
        foreach (['new', 'hit', 'recommend'] as $field) {
            $params[$field] = $request->has($field) ? 1 : 0;
        }

        $product->properties()->sync($request->property_id);

        $product->update($params);
        return redirect()->route('products.index')->with('success', 'Продукт обновлен.');
    }

    public function destroy(Product $product)
    {
        // if ($product->image) {
        //     Storage::disk('public')->delete($product->image);
        // }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Продукт удален.');
    }

}
