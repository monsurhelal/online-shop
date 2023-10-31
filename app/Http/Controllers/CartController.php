<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return view('front.checkout');
    }
}
