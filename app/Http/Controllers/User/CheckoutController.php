<?php

namespace App\Http\Controllers\User;

use App\Helper\CartHelper;
use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckoutController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'address' => 'required',
            'state'=>'required',
            'country_code'=>'required|max:3',
            "zipcode"=> 'required',
            'city'=>'required',
            'address_type'=> 'required'

        ]);
        
        $user = $request->user();
        $cartItems = $request->cartItems;
        $products = $request->products;
        
        $mergeArray = [];

        
// Loop through each item in cartItems
        foreach ($cartItems as $cartItem) {
            // Find the corresponding product in products array based on product_id
            $product = collect($products)->firstWhere('id', $cartItem['product_id']);

            // Check if a corresponding product is found
            if ($product) {
                // Merge cartItem and product data
                $mergedItem = array_merge($cartItem, ["title" => $product["title"], 'price' => $product['price']]);
                // Add the merged item to mergeArray
                $mergeArray[] = $mergedItem;
            }
        }

        // stripe payment
        $stripe = new \Stripe\StripeClient(env('STRIPE_KEY'));
        $LineItems = [];
        foreach( $mergeArray as $item){
           $LineItems [] = [ 'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                  'name' => $item['title'],
                ],
                'unit_amount' => (int)($item['price'] * 100),
              ],
              'quantity' => $item['quantity'],
            ];
        }


        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => $LineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success').'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel'),
          ]);


        $newAddress = $request->address;

        if($newAddress['address'] != null){
            $address = UserAddress::where('isMain',1)->count();
            if($address> 0){
                $address = UserAddress::where('isMain',1)->update(['isMain'=>0]);
            }
            $address = new UserAddress();
            $address->address1 = $newAddress['address'];
            $address->address2 = '';
            $address->state = $newAddress['state'];
            $address->city = $newAddress['city'];
            $address->country_code = $newAddress['country_code'];
            $address->zipcode = $newAddress['zipcode'];
            $address->type = $newAddress['address_type'];
            $address->user_id = Auth::user()->id;

            $address->save();

        }
        $mainAddress = $user->user_address()->where('isMain', 1)->first();
        if($mainAddress){
            $order= new Order();
            $order->status = 'unpaid';
            $order->total_price = $request->total;
            $order->session_id = $checkout_session->id;
            $order->created_by = $user->id;

            $order->user_address_id = $mainAddress->id;
            $order->save();
            $cartItems = CartItem::where(['user_id' => $user->id])->get();
            foreach($cartItems as $cartItem){
                OrderItem::create([
                    'order_id'=>$order->id,
                    'product_id'=> $cartItem->product_id,
                    'quantity'=> $cartItem->quantity,
                    'unit_price'=> $cartItem->product->price,
                ]);
                $cartItem->delete();
                
                $cartItems = CartHelper::getCookieCartItems();
                foreach($cartItems as $item){
                    unset($item);
                }
                array_splice($cartItems,0,count($cartItems));
                CartHelper::setCookieCartItems($cartItems);
            }
            
            $paymentData = [
                'order_id'=> $order->id,
                'amount'=>$request->total,
                'status'=> 'pending',
                'type'=>'stripe',
                'created_by' => $user->id,
                'updated_by'=> $user->id,
            ];
            Payment::create($paymentData);
        }
        return Inertia::location($checkout_session->url);
    }

    public function success(Request $request)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_KEY'));
        $sessionId = $request->get('session_id');
        try {
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
            if (!$session) {
                throw new NotFoundHttpException;
            }
            $order = Order::where('session_id', $session->id)->first();
            if (!$order) {
                throw new NotFoundHttpException();
            }
            if ($order->status === 'unpaid') {
                $order->status = 'paid';
                $order->save();
            }

            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }
    public function cancel(){
        return redirect()->route('home');
    }
}
