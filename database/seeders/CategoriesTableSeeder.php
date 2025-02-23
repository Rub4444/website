<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'Mobile Phones',
                'code' => 'mobile',
                'description' => 'Good mobiles...',
                'image' =>'categories/mobile.jpg'
            ],
            [
                'name' => 'Portable Technics',
                'code' => 'portable',
                'description' => 'There are portable tech...',
                'image' =>'categories/portable.jpg'
            ],
            [
                'name' => 'Appliance Technics',
                'code' => 'appliance',
                'description' => 'There are lot of technics.',
                'image' =>'categories/appliance.jpg'
            ],
        ]);
    }
}
