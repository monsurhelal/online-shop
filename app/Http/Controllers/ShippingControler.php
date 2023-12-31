<?php

namespace App\Http\Controllers;

use App\Models\country;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingControler extends Controller
{
    public function index(){
        
    }
    public function create(Request $request){
        $countries = country::all();
        $shippingCharges = Shipping::select('shippings.*','countries.name')
                                    ->leftJoin('countries','countries.id','shippings.country_id')->get();
        return view('admin.shipping.create',compact(['countries','shippingCharges']));
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'country' => 'required',
            'amount' => 'required'
        ]);

        if($validator->passes()){

            $dublicateCountry = Shipping::where('country_id',$request->country)->count();

            if($dublicateCountry > 0){
                session()->flash('error','this country already add');
                return response()->json([
                    'status' => true,
                ]);
            }

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
    public function edit(Request $request,$id){
        $countries = country::all();
        $shippingCharge = Shipping::find($id);
        return view('admin.shipping.edit',compact(['countries','shippingCharge']));
    }

    public function update(Request $request,$id){

        $validator = Validator::make($request->all(),[
            'country' => 'required',
            'amount' => 'required'
        ]);

        if($validator->passes()){

            $shipping = Shipping::find($id);
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

    public function destroy($id){
        $shipping = Shipping::find($id)->delete();
        session()->flash('error','shipping delete successfuly');
        return response()->json([
            'status' => true,
        ]);
    }
}
