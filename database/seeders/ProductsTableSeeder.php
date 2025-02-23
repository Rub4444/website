<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Iphone X 64GB',
                'code' => 'iphone_x_64',
                'description' => 'Good mobile phone.',
                'price' => '25000',
                'category_id' => 1,
                'image' => 'products/iphone_x.jpg',
            ],
            [
                'name' => 'HTC One S',
                'code' => 'htc_one_s',
                'description' => 'Legend mobile phone.',
                'price' => '12500',
                'category_id' => 1,
                'image' => 'products/htc_one_s.png',
            ],
            [
                'name' => 'Iphone 5 SE',
                'code' => 'iphone_5se',
                'description' => 'Good iphone.',
                'price' => '17000',
                'category_id' => 1,
                'image' => 'products/iphone_5.jpg',
            ],
        ]);
    }
}
