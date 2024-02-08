<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(){
        $products = Product::with('category','brand','product_images')->get();
        $categories = Category::get();
        $brands = Brand::get();
        return Inertia::render("Admin/Products/Index",
        ["products"=> $products,
        "categories"=> $categories,
        "brands" => $brands
        ]
    );
    }

    public function store(Request $request){

        $request->validate([
            "title"=> "required|max:200",
            "price"=> "required|numeric",
            "quantity" => "required|integer",
            "brand_id"=> "required",
            "category_id"=> "required",
            "description"=>"required",

        ]);

        $product = new Product();
        $product->title = $request->title;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->description = $request->description;
        $product->brand_id = $request->brand_id;


        $product->save();

        if($request->hasFile("product_images")){
            $productImages = $request->file("product_images");

            foreach($productImages as $image){
                $uniqueName = time().'-'. Str::random(10).'.'.$image->getClientOriginalExtension();
                $image->move('product_images',$uniqueName);

                ProductImage::create([
                    'product_id'=>$product->id,
                    'image'=>'product_images/'.$uniqueName,
                ]);
            }
        }
        return redirect()->route('admin.products.index')->with('success','Product created successfully');
    }

    public function deleteImage($id){
        $image = ProductImage::where('id',$id)->delete();
        return redirect()->route('admin.products.index')->with('success',"Image deleted Successfully");
    }

    public function update(Request $request,$id){
        $product = Product::findOrFail($id);

        // Update the product details
        $product->title = $request->title;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->brand_id = $request->brand_id;
        $product->category_id = $request->category_id;
        $product->description = $request->description;  
        if($request->hasFile('product_images')){
            $productImages = $request->file('product_images');

            foreach($productImages as $image){
                $uniqueName = time().'-'.Str::random(10).'.'.$image->getClientOriginalExtension();
                $image->move('product_images',$uniqueName);
                ProductImage::create([
                    'product_id'=>$id,
                    'image'=> 'product_images/'.$uniqueName,
                ]);
            }
        }

        $product->update();
        return redirect()->back()->with('success','Product Updated Successfully');

    }

}
