<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Հյութեր',
            'Ալկոհոլային խմիչքներ',
            'Կաթնամթերք',
            'Կիսաֆաբրիկատ',
            'Միրգ և Բանջարեղեն',
            'Պահածոյացված',
            'Քաղցրավենիք',
            'Ընդեղեն',
            'Հացաբուլկեղեն',
            'Պաղպաղակ',
            'Մաքրող միջոց',
            'Լվացքի միջոց',
            'Հիգիենայի պարագաներ',
            'Զարդեր',
            'Խնամքի միջոց',
            'Սպասք',
            'Հագուստ',
            'Էլեկտրոնիկա',
            'Սպիտակեղեն',
            'Խաղալիքներ',
            'Գրենական',
            'Խոհանոցային պարագաներ',
            'Այլ տնտեսական ապրանքներ',
        ];

        foreach ($categories as $category) {
            $slug = Str::slug($category, '-');

            Category::create([
                'name' => $category,
                'code' => $slug,
                'description' => $category . ' բաժնի ապրանքներ։',
                'image' => null, // Добавьте путь к изображению, если есть
                'name_en' => $slug, // Можно заменить на нормальный перевод
                'description_en' => 'Products in the ' . $slug . ' category.',
            ]);
        }
    }
}
