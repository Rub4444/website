<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $AllCategories = Category::paginate(30);
        return view('auth.Categories.index', compact('AllCategories'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auth.Categories.form');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('auth.Categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('auth.Categories.form', compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(CategoryRequest $request)
{
    $params = $request->all();

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('categories', 'public');
        $params['image'] = $path;
    }

    Category::create($params);
    Cache::forget('view_categories');

    return redirect()->route('categories.index')
        ->with('success', 'Категория создана успешно!');
}


    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
{
    $params = $request->all();

    // Если загружено новое изображение
    if ($request->hasFile('image')) {
        // Удаляем старое изображение, если есть
        if ($category->image) {
            $filePath = public_path('storage/' . $category->image);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Сохраняем новое
        $path = $request->file('image')->store('categories', 'public');
        $params['image'] = $path;
    }

    $category->update($params);
    Cache::forget('view_categories');

    return redirect()->route('categories.index')
        ->with('success', 'Категория обновлена успешно!');
}



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
{
    // Удаляем изображение из хранилища
    if ($category->image) {
        $filePath = public_path('storage/' . $category->image);
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    $category->delete();
    Cache::forget('view_categories');

    return redirect()->route('categories.index')
        ->with('success', 'Категория удалена успешно!');
}

}
