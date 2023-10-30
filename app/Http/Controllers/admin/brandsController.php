<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\brads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class brandsController extends Controller
{

    public function index(Request $request){

        $brandes = brads::latest();
                                        
        if(!empty($request->get('keyWord'))){
            $brandes = $brandes->where('name','like','%'.$request->get('keyWord').'%');
        }
        $brandes = $brandes->paginate(10);

        return view('admin.brands.list',compact('brandes'));
        
    }
    public function create(){

        return view('admin.brands.create');
    }

    public function store(Request $request){


        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required',
            'status' => 'required',
        ]);

        if($validator->passes()){

            $brand = new brads();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            session()->flash('success','brands add successfuly');

            return response([
                'status' => true,
                'errors' =>'brands add successfuly'
            ]);
        }else{

            return response([
                'status' => false,
                'errors' =>$validator->errors()
            ]);
        }
        
    }


    public function edit(Request $request,$id){

        $brand = brads::find($id);

        if (empty($brand)) {
            session()->flash('error','recorde not found');
            return redirect()->route('brands.index');
        }

        return view('admin.brands.edit',compact('brand'));

    }

    public function update(Request $request,$id){

        $brand = brads::find($id);

        if (empty($brand)) {
            session()->flash('error','recorde not found');

            return response ()->json([
                'status' => false,
                'notFound' => true
            ]);
            //return redirect()->route('brands.index');
        }


        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required',
            'status' => 'required',
        ]);

        if($validator->passes()){

            $brand = brads::find($id);
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            session()->flash('success','brands add successfuly');

            return response([
                'status' => true,
                'errors' =>'brands add successfuly'
            ]);
        }else{

            return response([
                'status' => false,
                'errors' =>$validator->errors()
            ]);
        }


    }

    public function delete($id){

        $deleteBrand = brads::find($id)->delete();

        return response()->json([
            'status' => true
        ]);

    }
}
