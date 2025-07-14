<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SkuRequest;
use App\Models\Product;
use App\Models\Sku;
use Illuminate\Http\Request;

class SkuController extends Controller
{
    public function index(Product $product)
    {
        $skus = $product->skus()->with('propertyOptions.property')->paginate(10);
        return view('auth.skus.index', compact('skus', 'product'));
    }

    public function create(Product $product)
    {
         // Загрузим вместе с propertyOptions
        $product->load('properties.propertyOptions');

        return view('auth.skus.form', compact('product'));
    }

   public function store(SkuRequest $request, Product $product)
    {
        $params = $request->validated();
        $params['price'] = $params['price'];
        $params['product_id'] = $product->id;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('skus', 'public');
            $params['image'] = $path;
        }

        $sku = Sku::create($params); // ← создаём после добавления image

        if ($request->has('property_id')) {
            $sku->propertyOptions()->sync($request->property_id);
        }

        return redirect()->route('skus.show', [$product, $sku->id])
                        ->with('success', 'Ապրանքային առաջարկը հաջողությամբ ստեղծվեց։');
    }


    public function show(Product $product, Sku $sku)
    {
        $sku->load('propertyOptions.property');

        return view('auth.skus.show', compact('product', 'sku'));
    }

    public function edit(Product $product, Sku $sku)
    {
         // Загрузим вместе с propertyOptions
        $product->load('properties.propertyOptions');

        return view('auth.skus.form', compact('product', 'sku'));
    }

    public function update(SkuRequest $request, Product $product, Sku $sku)
{
    $params = $request->validated();
    $params['product_id'] = $product->id;

    if ($request->hasFile('image')) {
        if ($sku->image) {
            Storage::disk('public')->delete($sku->image);
        }

        $params['image'] = $request->file('image')->store('skus', 'public');
    }

    $sku->update($params); // ← переместить сюда после image

    if ($request->has('property_id')) {
        $sku->propertyOptions()->sync($request->property_id);
    }

    return redirect()->route('skus.show', [$product, $sku->id])
                     ->with('success', 'Ապրանքային առաջարկը հաջողությամբ թարմացվեց։');
}


    public function destroy(Product $product, Sku $sku)
    {
        $sku->delete();
        return redirect()->route('skus.index', $product)
                         ->with('success', 'Ապրանքային առաջարկը հաջողությամբ հեռացվեց։');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $skus = Sku::with(['product', 'propertyOptions.property'])
                    ->whereHas('product', function ($q) use ($query) {
                        $q->where('name', 'like', '%' . $query . '%');
                    })
                    ->orWhere('price', 'like', '%' . $query . '%') // Пример: поиск по цене
                    ->paginate(12);

        return view('products.search-results', compact('skus', 'query'));
    }

}
