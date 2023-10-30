<?php

namespace App\Http\Controllers;

use App\Models\brads;
use App\Models\Category;
use App\Models\Products;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(){

        $categories = Category::orderBy('name','ASC')->where('status',1)->with('get_sub_category')->get();
        //dd($categories);
        $brands = brads::orderBy('name','ASC')->where('status',1)->get();
        $Products = Products::orderBy('id','DESC')->where('status',1)->with('product_images')->get();

        return view('front.shop',compact(['categories','brands','Products']));
    }

    public function product ($slug){

        $product = Products::where('slug',$slug)->with('product_images')->first();

        if($product === null){
            abort(404);
        }

        return view('front.product',compact('product'));

    }
}
