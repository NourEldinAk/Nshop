<?php 

namespace App\Helper;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cookie;

class CartHelper{
    public static function getCount(){
     if($user = auth()->user()){
        return CartItem::whereUserId($user->id)->sum('quantity');
        }
    }

    public static function getCartItems(){
        if($user = auth()->user()){
            return CartItem::whereUserId($user->id)->get()
            ->map(fn(CartItem $item)=> ['product_id' => $item->product_id,'quantity'=> $item->quantity]);
        }
    }

    public static function getCookieCartItems(){
        return json_decode(request()->cookie('cart_items','[]'), true);
    }
    
    // public static function setCookieCartItems(){
    //     Cookie::queue('cart_items',
    //     fn(int $carry ,array $item)
    //     => $carry + $item['quantity'],0);
    // }
    public static function setCookieCartItems($cartItems){
        $totalQuantity = array_reduce($cartItems, fn(int $carry, array $item) => $carry + $item['quantity'], 0);
        Cookie::queue('cart_items', $totalQuantity);
    }

    public static function saveCookieCartItems(){
        $user = auth()->user();
        $userCartItems = CartItem::where(['user_id'=> $user->id])->get()->keyBy('product_id');
        $savedCartItems = [];

        foreach(self::getCookieCartItems() as $item){
            if(isset($userCartItems[$item['product_id']])){
                $userCartItems[$item['product_id']]->update(['quantity'=> $item['quantity']]);
                continue;
            }
            $savedCartItems[] = [
                'product_id'=> $item['product_id'],
                'quantity'=> $item['quantity'],
                'user_id'=> $user->id,
            ];
            
        }
        if(!empty($savedCartItems)){
            CartItem::insert($savedCartItems);
        }

    }
    public static function moveCartItemsToDb(){
        $request = request();
        $cartItems = self::getCookieCartItems();
        $newCartItems = [];

        foreach($cartItems as $item){
           $existingCartItem = CartItem::where(['product_id'=> $item['product_id'],
            'user_id' => $request->user->id])->first();
            if(!$existingCartItem){

                $newCartItems[]= [
                    'product_id'=> $item['product_id'],
                    'quantity'=> $item['quantity'],
                    'user_id'=> $request->user->id,
                ];
            }

            if(!empty($newCartItems)){
                CartItem::insert($newCartItems);
            }
        }
    }

    public static function getProductsAndCartItems(){
        $cartItems = self::getCartItems();
        $ids = [];
        if ($cartItems) {
            $ids = Arr::pluck($cartItems, 'product_id');
        }       
        $products = Product::whereIn('id', $ids)->with('product_images')->get();
        $cartItems = Arr::keyBy($cartItems,'product_id');
        return [$products , $cartItems];
    }
}