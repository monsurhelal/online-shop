<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\sub_categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    public function index(Request $request){

        $sub_categories = sub_categories::select('sub_categories.*','categories.name as categoryName')
                                        ->latest()
                                        ->leftJoin('categories','categories.id','sub_categories.category_id');

        if(!empty($request->get('keyWord'))){
            $sub_categories = $sub_categories->where('name','like','%'.$request->get('keyWord').'%');
        }
        $sub_categories = $sub_categories->paginate(10);

        return view('admin.sub_category.list',compact('sub_categories'));
        
    }
    public function create(){

        $categories = Category::orderBy('name','ASC')->get();
        return view('admin.sub_category.create',compact('categories'));
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:sub_categories',
            'category' => 'required',
            'status' => 'required'
        ]);

        if($validator->passes()){

            $subCategory = new sub_categories();
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->category_id = $request->category;
            $subCategory->save();

            session()->flash('success','sub category add successfuly');

            return response([
                'status' =>true,
                'message' =>'sub category add successfuly'
            ]);

        }else{

            return response([
                'status' => false,
                'errors' =>$validator->errors()
            ]);

        }
    }
}
