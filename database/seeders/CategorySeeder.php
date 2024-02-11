<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Category::create([
        //     "name"=> "Computer",
        //     "slug"=> "computer",
        // ]);
        // Category::create([
        //     "name"=> "Headphones",
        //     "slug"=> "headphones",
        // ]);
        // Category::create([
        //     "name"=> "Watch",
        //     "slug"=> "watch",
        // ]);
        // Category::create([
        //     "name"=> "Clothes",
        //     "slug"=> "clothes",
        // ]);
        Category::create([
            "name"=> "Other",
            "slug"=> "other",
        ]);
    }
}
