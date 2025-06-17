<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

// class ProductsImport implements ToModel, WithHeadingRow
// {
//     public function model(array $row)
//     {
//         \Log::info($row);

//         $name = $row['naimenovanie'] ?? 'unknown';
//         $code = Str::slug($name);

//         // Создаём товар
//         $product = new Product([
//             'name' => $name,
//             'code' => $code,
//             'category_id' => 2,  // жёстко заданная категория
//         ]);

//         // Сохраняем, чтобы получить id
//         $product->save();

//         // Жёстко задаём свойство (например id = 5)
//         $product->properties()->sync([1]);

//         return $product;
//     }
// }

class ProductsImport implements ToModel, WithHeadingRow
{
    protected $categoryId;
    protected $propertyIds;

    public function __construct($categoryId, $propertyIds)
    {
        $this->categoryId = $categoryId;
        $this->propertyIds = $propertyIds;
    }

    public function model(array $row)
    {
        $name = $row['naimenovanie'] ?? 'unknown';
        $code = Str::slug($name);

        $product = new Product([
            'name' => $name,
            'code' => $code,
            'category_id' => $this->categoryId,
        ]);

        $product->save();

        $product->properties()->sync($this->propertyIds);

        return $product;
    }
}
