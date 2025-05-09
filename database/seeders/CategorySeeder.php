<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Հյութեր', 'icon' => 'fa-solid fa-wine-glass-alt'],
            ['name' => 'Ալկոհոլային խմիչքներ', 'icon' => 'fa-solid fa-glass-whiskey'],
            ['name' => 'Կաթնամթերք', 'icon' => 'fa-solid fa-cheese'],
            ['name' => 'Կիսաֆաբրիկատ', 'icon' => 'fa-solid fa-drumstick-bite'],
            ['name' => 'Միրգ և Բանջարեղեն', 'icon' => 'fa-solid fa-apple-alt'],
            ['name' => 'Պահածոյացված', 'icon' => 'fa-solid fa-jar'],
            ['name' => 'Քաղցրավենիք', 'icon' => 'fa-solid fa-candy-cane'],
            ['name' => 'Ընդեղեն', 'icon' => 'fa-solid fa-seedling'],
            ['name' => 'Հացաբուլկեղեն', 'icon' => 'fa-solid fa-bread-slice'],
            ['name' => 'Պաղպաղակ', 'icon' => 'fa-solid fa-ice-cream'],

            ['name' => 'Մաքրող միջոց', 'icon' => 'fa-solid fa-soap'],
            ['name' => 'Լվացքի միջոց', 'icon' => 'fa-solid fa-pump-soap'],
            ['name' => 'Հիգիենայի պարագաներ', 'icon' => 'fa-solid fa-toilet-paper'],
            ['name' => 'Զարդեր', 'icon' => 'fa-solid fa-gem'],
            ['name' => 'Խնամքի միջոց', 'icon' => 'fa-solid fa-hand-sparkles'],
            ['name' => 'Սպասք', 'icon' => 'fa-solid fa-utensils'],
            ['name' => 'Հագուստ', 'icon' => 'fa-solid fa-tshirt'],
            ['name' => 'Էլեկտրոնիկա', 'icon' => 'fa-solid fa-tv'],
            ['name' => 'Սպիտակեղեն', 'icon' => 'fa-solid fa-bed'],
            ['name' => 'Խաղալիքներ', 'icon' => 'fa-solid fa-puzzle-piece'],
            ['name' => 'Գրենական', 'icon' => 'fa-solid fa-pencil-alt'],
            ['name' => 'Խոհանոցային պարագաներ', 'icon' => 'fa-solid fa-blender'],
            ['name' => 'Այլ տնտեսական ապրանքներ', 'icon' => 'fa-solid fa-box-open'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'code' => \Str::slug($category['name']),
                'description' => null,
                'image' => null,
                'name_en' => null,
                'description_en' => null,
                'icon' => $category['icon'],
            ]);
        }
    }
}
