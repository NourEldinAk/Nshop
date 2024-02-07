<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            "title"=> "Apple Watch",
            "price"=> 149.03,
            "quantity"=>4,
            "category_id"=> 3,
            "brand_id"=>3,
            "description" =>"this is a really nice watch for every day life",

        ]);
    }
}
