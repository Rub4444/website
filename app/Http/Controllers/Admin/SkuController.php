<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SkuRequest;
use App\Models\Product;
use App\Models\Sku;
use App\Models\PropertyOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SkuController extends Controller
{
    public function index(Product $product, Request $request)
    {
        $search = $request->input('search');

        $skusQuery = $product->skus()->with('propertyOptions.property');

        if ($search) {
            $skusQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('price', 'like', "%$search%")
                  ->orWhereHas('propertyOptions', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%$search%");
                  });
            });
        }

        $skus = $skusQuery->paginate(50)->withQueryString();

        return view('auth.skus.index', compact('skus', 'product', 'search'));
    }

    public function create(Product $product)
    {
        return view('auth.skus.form', compact('product'));
    }

    public function store(SkuRequest $request, Product $product)
    {
        $params = $request->validated();
        $params['name'] = $request->input('name', null);
        $params['product_id'] = $product->id;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('skus', 'public');
            $params['image'] = $path;
        }

        $sku = Sku::create($params);

        // Обработка свойств
        if ($request->has('property_id')) {
            $propertyOptions = [];

            foreach ($request->input('property_id') as $propertyId => $value) {
                if (!empty($value)) {
                    $option = PropertyOption::firstOrCreate([
                        'property_id' => $propertyId,
                        'name' => $value,
                    ]);
                    $propertyOptions[] = $option->id;
                }
            }

            $sku->propertyOptions()->sync($propertyOptions);
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
        return view('auth.skus.form', compact('product', 'sku'));
    }

    public function update(SkuRequest $request, Product $product, Sku $sku)
    {
        $params = $request->validated();
        $params['name'] = $request->input('name', null);
        $params['product_id'] = $product->id;

        if ($request->hasFile('image')) {
            if (!empty($sku->image) && Storage::disk('public')->exists($sku->image)) {
                Storage::disk('public')->delete($sku->image);
            }

            $params['image'] = $request->file('image')->store('skus', 'public');
        }

        $sku->update($params);

        // Обновляем свойства
        if ($request->has('property_id')) {
            $propertyOptions = [];

            foreach ($request->input('property_id') as $propertyId => $value) {
                if (!empty($value)) {
                    $option = PropertyOption::firstOrCreate([
                        'property_id' => $propertyId,
                        'name' => $value,
                    ]);
                    $propertyOptions[] = $option->id;
                }
            }

            $sku->propertyOptions()->sync($propertyOptions);
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

        $skus = Sku::with('product')
            ->whereHas('product', function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%');
            })
            ->orWhere('name', 'like', '%' . $query . '%')
            ->get();

        return view('products.search-results', compact('skus', 'query'));
    }
}
