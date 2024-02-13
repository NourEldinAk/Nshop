<?php

namespace App\Http\Controllers\User;

use App\Helper\CartHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function view(Request $request, Product $product){
        $user = $request->user();
        if($user){
            $cartItems = CartItem::where("user_id", $user->id)->get();
            $userAddress = UserAddress::where("user_id", $user->id)->where('isMain',1)->first();
            return Inertia::render('User/CartList',[
                'cartItems'=> $cartItems,
                'userAddress' => $userAddress,
            ]);
        }else{
            $cartItems = CartHelper::getCookieCartItems();
            if(count($cartItems) > 0){
                $cartItems = new CartResource(CartHelper::getProductsAndCartItems());
                
            }else{
                // return redirect()->back()->with('info','Your Cart Is Empty');
            }
            
            return Inertia::render('User/CartList',[
                'cartItems'=> $cartItems,
            ]);
        }
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
                    "quantity"=> 1,
                    "product_id"=> $product->id
                ]);
            }
        }else{
            $cartItems = CartHelper::getCookieCartItems();
            $isProductExist = false;
            foreach($cartItems as $cartItem){
                if($cartItem['product_id'] == $product->id){
                    $cartItem['quantity']+= $quantity;
                    $isProductExist = true;
                    break;
                }

            }
            
            if(!$isProductExist){
                $cartItems[]=[
                    'user_id'=> null,
                    'quantity'=> $quantity,
                    'product_id'=> $product->id,
                    'price'=>$product->price,
                ];
            }

            CartHelper::setCookieCartItems($cartItems);

        }
    }
    public function update(Request $request,Product $product){
        $user = $request->user();
        $quantity= $request->integer('quantity');
        if($user){
            CartItem::where(['user_id' => $user->id, 'product_id'=>$product->id])
            ->update(['quantity'=>$quantity]);
        }else{
            $cartItems = CartHelper::getCookieCartItems();
            foreach ($cartItems as &$item) {
                if ($item['product_id'] === $product->id) {
                    $item['quantity'] = $quantity;
                    break;
                }
            }
            CartHelper::setCookieCartItems($cartItems);
        }    
    }
    public function delete(Request $request, Product $product)
    {
        $user = $request->user();
        if ($user) {
            CartItem::query()->where(['user_id' => $user->id, 'product_id' => $product->id])->first()?->delete();
            if (CartItem::count() <= 0) {
                return redirect()->route('home')->with('info', 'your cart is empty');
            } else {
                return redirect()->back()->with('success', 'item removed successfully');
            }
        } else {
            $cartItems = CartHelper::getCookieCartItems();
            foreach ($cartItems as $i => &$item) {
                if ($item['product_id'] === $product->id) {
                    array_splice($cartItems, $i, 1);
                    break;
                }
            }
            CartHelper::setCookieCartItems($cartItems);
            if (count($cartItems) <= 0) {
                return redirect()->route('home')->with('info', 'your cart is empty');
            } else {
                return redirect()->back()->with('success', 'item removed successfully');
            }
        }
    }
}
