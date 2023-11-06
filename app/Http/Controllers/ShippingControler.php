<?php

namespace App\Http\Controllers;

use App\Models\country;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingControler extends Controller
{
    public function create(Request $request){
        $countries = country::all();
        return view('admin.shipping.create',compact('countries'));
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'country' => 'required',
            'amount' => 'required'
        ]);

        if($validator->passes()){

            $shipping = new Shipping;
            $shipping->country_id = $request->country;
            $shipping->amount = $request->amount;
            $shipping->save();
            return response()->json([
                'status' => true,
                'message' => 'shipping successfuly add'
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

    }
}
