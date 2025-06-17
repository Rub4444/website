<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;

class ProductImportController extends Controller
{
    // public function import(Request $request)
    // {

    //     $request->validate([
    //         'file' => 'required|file|mimes:xls,xlsx',
    //     ]);

    //     Excel::import(new ProductsImport, $request->file('file'));

    //     return redirect()->back()->with('success', 'Импорт выполнен успешно!');
    // }

    public function import(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xls,xlsx',
        'category_id' => 'required|integer|exists:categories,id',
        'property_id' => 'required|string',
    ]);

    $categoryId = $request->input('category_id');

    // Разбиваем строку property_id по запятым и фильтруем числа
    $propertyIds = array_filter(array_map('trim', explode(',', $request->input('property_id'))), function($id) {
        return is_numeric($id);
    });

    Excel::import(new ProductsImport($categoryId, $propertyIds), $request->file('file'));

    return redirect()->back()->with('success', 'Импорт выполнен успешно!');
}

}
