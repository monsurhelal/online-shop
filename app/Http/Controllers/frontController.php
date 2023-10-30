<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;

class frontController extends Controller
{
    public function index(){
        $featuredProducts = Products::where('is_featured','yes')->where('status',1)->get();
        $latestProducts = Products::orderBy('id','ASC')->where('status',1)->take(8)->get();
        return view('front.home',compact(['featuredProducts','latestProducts']));
    }

}
