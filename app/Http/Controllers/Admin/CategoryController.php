<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
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
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {

        $params = $request->all();
        unset($params['image']);
        if($request->has('image'))
        {
            $path = $request->file('image')->store('categories', 'public');
            $params['image'] = $path;
        }

        Category::create($params);
        return redirect()->route('auth.Categories.index');
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
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        // Проверяем, если старое изображение существует, удаляем его
        if ($category->image)
        {
            $filePath = public_path('storage/' . $category->image); // Путь к файлу
            if (file_exists($filePath))
            {
                unlink($filePath); // Удаляем файл
            }
        }

        // Проверяем, если файл загружен, сохраняем его
        $params = $request->all();
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public'); // Сохраняем новый файл
            $params['image'] = $path; // Обновляем путь к изображению
        }

        // Обновляем данные категории
        $category->update($params);

        // Перенаправляем обратно в список категорий
        return redirect()->route('auth.Categories.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('auth.Categories.index');
    }
}
