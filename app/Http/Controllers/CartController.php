<?php

namespace App\Http\Controllers;

use App\Models\country;
use App\Models\customer_address;
use App\Models\order;
use App\Models\order_item;
use App\Models\Products;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function AddToCart(Request $request){
         $product = Products::with('product_images')->find($request->id);

         if($product == null){
            return response()->json([
                'status' =>false,
                'message' =>'product not found',
            ]);
         }

         if(Cart::count() > 0){

            //echo "product already in cart";

            $cartContect = Cart::content();
            $productAllreadyExit = false;

            foreach($cartContect as $item){
                if ($item->id == $product->id) {
                    $productAllreadyExit = true;
                }
            }

            if($productAllreadyExit == false){
                Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => $product->product_images->first()]);
                return response()->json([
                    'status' =>true,
                    'message' =>'product add in cart'
                ]);

            }else{
                return response()->json([
                    'status' =>false,
                    'message' =>'product allready exits in cart'
                ]);
            }

         }else{
            Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => $product->product_images->first()]);
            return response()->json([
            'status' =>true,
            'message' =>'product add in cart'
        ]);
         }

    }
    public function cart(){
        $cartContect = Cart::content();
        //dd($cartContect);
        return view('front.cart',compact('cartContect'));
    }

    public function updateCart(Request $request){

        $rowId = $request->rowId;
        $qty = $request->qty;

        Cart::update($rowId, $qty);
        return response()->json([
            'status' => true,
            'message' => 'cart upddate successfuly'
        ]);
    }

    public function deleteItem(Request $request){
        Cart::remove($request->rowId);
        return response()->json([
            'status' => true,
            'message' => 'cart delete successfuly'
        ]);
    }

    public function checkout(){
        if(Cart::count() == 0){
            return redirect()->route('shop.cart');
        }
        if(Auth::check() == false){
            if(!session()->has('url.intended')){
                session(['url.intended' => url()->current()]);
            }
            
            return redirect()->route('user.login');
        }
        session()->forget('url.intended');
        $customarAddress = customer_address::where('user_id',Auth::user()->id)->first();
        $countries = country::orderBy('name','ASC')->get();
        return view('front.checkout',compact(['countries','customarAddress']));
    }

    public function processCheckout(Request $request){

        $validator = Validator::make($request->all(),[
            'first_name' =>'required',
            'last_name' =>'required',
            'email' =>'required',
            'country' =>'required',
            'address' =>'required',
            'appartment' =>'required',
            'city' =>'required',
            'state' =>'required',
            'zip' =>'required',
            'mobile' =>'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=> false,
                'message'=> 'please fix the error',
                'error'=> $validator->errors(),
            ]);
        }
        // customar address table 
        $user = Auth::user();

        customer_address::updateOrCreate(
            ['user_id'=>$user->id],
            [
                'user_id'=>$user->id,
                'fast_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'country_id' => $request->country,
                'address' => $request->address,
                'aparmemt' => $request->appartment,
                'city' => $request->city,
                'status' => $request->state,
                'zip' => $request->zip,
            ]
        );

        // ordet table save data 

        if($request->payment_method == 'cod'){
            $shiping = 0;
            $discount = 0;
            $subtotal = Cart::subtotal(2,'.','');
            $grandtotal = $subtotal + $shiping;
            $order = new order;
            $order->subtotal = $subtotal;
            $order->shiping = $shiping;
            $order->grant_total = $grandtotal;
            $order->user_id = $user->id;
            $order->fast_name =$request->first_name;
            $order->last_name =  $request->last_name;
            $order->email = $request->email;
            $order->mobile = $request->mobile;
            $order->country_id =$request->country;
            $order->address = $request->address;
            $order->aparmemt = $request->appartment;
            $order->city = $request->city;
            $order->status =$request->state;
            $order->zip = $request->zip;
            $order->notes = $request->order_notes;
            $order->save();

            // order item table save data
            foreach (Cart::content() as $item) {
                $items = new order_item;
                $items->order_id = $order->id;
                $items->product_id = $item->id;
                $items->name = $item->name;
                $items->qty = $item->qty;
                $items->price = $item->price;
                $items->total = $order->price * $item->qty;
                $items->save();
            }
            session()->flash('success','you have successfuly place your order');
            Cart::destroy();
            return response()->json([
                'status'=> true,
                'orderId'=> $order->id,
                'message'=> 'order save successfuly',
            ]);

        }else{

        }

    }

    public function thankYou(){
        return view('front.thanks');
    }
}
