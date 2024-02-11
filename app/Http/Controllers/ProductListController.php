<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductListController extends Controller
{   
    public function index(){
        $products= Product::with('brand','category','product_images');
        $filterProducts = $products->filtered()->paginate(9)->withQueryString();
        $categories = Category::get();
        $brands = Brand::get();

        return Inertia::render("User/ProductList",[
            "categories" =>$categories,
            "brands"=> $brands,
            "products"=>ProductResource::collection($filterProducts),
        ]);
    }
}
