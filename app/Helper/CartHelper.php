<?php 

namespace App\Helper;

use App\Models\CartItem;
use Illuminate\Support\Facades\Cookie;

class CartHelper{
    public static function getCount($user){
     if($user == auth()->user()){
        return CartItem::whereUserId($user->id)->sum('quantity');
        }
    }

    public static function getCartItems($user){
        if($user == auth()->user()){
            return CartItem::whereUserId($user->id)->get()
            ->map(fn(CartItem $item)=> ['product_id' => $item->product_id,'quantity'=> $item->quantity]);
        }
    }

    public static function getCookieCartItems(){
        return json_decode(request()->cookie('cart_items','[]'), true);
    }
    
    public static function setCookieCartItems($item){
        Cookie::queue('cart_items',
        fn(int $carry ,Array $item)
        => $carry + $item['quantity'],0);
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
}