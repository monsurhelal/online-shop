<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\sub_categories;
use Illuminate\Http\Request;

class ProductSubCategoryControlller extends Controller
{
    public function index(Request $request){
        if(!empty($request->categpry_id)){

            $sub_categories = sub_categories::where('category_id',$request->categpry_id)->orderBy('name','ASC')->get();
        return response()->json([
            'status' => true,
            'sub_categories' => $sub_categories
        ]);
        }else{
            return response()->json([
                'status' => true,
                'sub_categories' =>[]
            ]);
        }
        
    }
}
