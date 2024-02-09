<?php

namespace App\Http\Controllers\User;

use App\Helper\CartHelper;
use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function view(){

    }
    public function store(Request $request,Product $product){
        $quantity = $request->post("quantity", 1);
        $user = $request->user();

        if($user){
            $cartItems = CartItem::where(["user_id"=> $user->id, "product_id" => $product->id])->first();
            if($cartItems){
                $cartItems->increment("quantity");
            }else{
                CartItem::create([
                    "user_id"=> $user->id,
                    "quantity"=> $quantity,
                    "product_id"=> $product->id
                ]);
            }
        }else{
            $cartItems = CartHelper::getCookieCartItems();
            $isProductExist = false;
            foreach($cartItems as $cartItem){
                if($cartItem->product_id == $product->id){
                    $cartItem['quantity']+= $quantity;
                    $isProductExist = true;
                    break;
                }

                if(!$isProductExist){
                    $cartItems[]=[
                        'user_id'=> $user->id,
                        'quantity'=> $quantity,
                        'product_id'=> $product->id,
                        'price'=>$product->price,
                    ];
                }
                CartHelper::setCookieCartItems($cartItems);
            }

        }
        return redirect()->back()->with('success','Product Added To Cart');
    }
    public function update(Request $request,Product $product){

    }
    public function delete(Request $request,Product $product){

    }
}
