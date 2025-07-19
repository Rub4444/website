<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Զովացուցիչ ըմպելիքներ / Ջուր', 'name_en' => 'Juices', 'icon' => 'fa-solid fa-wine-glass-alt'],
            ['name' => 'Ալկոհոլային խմիչքներ', 'name_en' => 'Alcoholic Drinks', 'icon' => 'fa-solid fa-glass-whiskey'],
            ['name' => 'Կաթնամթերք', 'name_en' => 'Dairy Products', 'icon' => 'fa-solid fa-cheese'],
            ['name' => 'Կիսաֆաբրիկատ', 'name_en' => 'Semi-finished', 'icon' => 'fa-solid fa-drumstick-bite'],
            ['name' => 'Միրգ և Բանջարեղեն', 'name_en' => 'Fruits and Vegetables', 'icon' => 'fa-solid fa-apple-alt'],
            ['name' => 'Պահածոյացված', 'name_en' => 'Canned Goods', 'icon' => 'fa-solid fa-jar'],
            ['name' => 'Քաղցրավենիք', 'name_en' => 'Sweets', 'icon' => 'fa-solid fa-candy-cane'],
            ['name' => 'Ընդեղեն', 'name_en' => 'Nuts and Seeds', 'icon' => 'fa-solid fa-seedling'],
            ['name' => 'Հացաբուլկեղեն', 'name_en' => 'Bakery', 'icon' => 'fa-solid fa-bread-slice'],
            ['name' => 'Պաղպաղակ', 'name_en' => 'Ice Cream', 'icon' => 'fa-solid fa-ice-cream'],
            ['name' => 'Չիպսեր և սերմեր', 'name_en' => 'Chips and Seeds', 'icon' => 'fa-solid fa-cookie'],
            ['name' => 'Ծխախոտներ', 'name_en' => 'Cigarettes', 'icon' => 'fa-solid fa-smoking'],

            ['name' => 'Մաքրող միջոց', 'name_en' => 'Cleaning Supplies', 'icon' => 'fa-solid fa-soap'],
            ['name' => 'Լվացքի միջոց', 'name_en' => 'Laundry Products', 'icon' => 'fa-solid fa-pump-soap'],
            ['name' => 'Հիգիենայի պարագաներ', 'name_en' => 'Hygiene Items', 'icon' => 'fa-solid fa-toilet-paper'],
            ['name' => 'Զարդեր', 'name_en' => 'Jewelry', 'icon' => 'fa-solid fa-gem'],
            ['name' => 'Խնամքի միջոց', 'name_en' => 'Care Products', 'icon' => 'fa-solid fa-hand-sparkles'],
            ['name' => 'Սպասք', 'name_en' => 'Tableware', 'icon' => 'fa-solid fa-utensils'],
            ['name' => 'Հագուստ', 'name_en' => 'Clothing', 'icon' => 'fa-solid fa-tshirt'],
            ['name' => 'Էլեկտրոնիկա', 'name_en' => 'Electronics', 'icon' => 'fa-solid fa-tv'],
            ['name' => 'Սպիտակեղեն', 'name_en' => 'Bedding', 'icon' => 'fa-solid fa-bed'],
            ['name' => 'Խաղալիքներ', 'name_en' => 'Toys', 'icon' => 'fa-solid fa-puzzle-piece'],
            ['name' => 'Գրենական', 'name_en' => 'Stationery', 'icon' => 'fa-solid fa-pencil-alt'],
            ['name' => 'Խոհանոցային պարագաներ', 'name_en' => 'Kitchenware', 'icon' => 'fa-solid fa-blender'],
            ['name' => 'Այլ տնտեսական ապրանքներ', 'name_en' => 'Other Household Products', 'icon' => 'fa-solid fa-box-open'],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'code' => Str::slug($category['name']),
                'description' => null,
                'image' => null,
                'name_en' => $category['name_en'],
                'description_en' => null,
                'icon' => $category['icon'],
            ]);
        }
    }
}
