<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;
use Image;

class categoryController extends Controller
{
    public function index(Request $request){

        $categories = Category::latest();

        if(!empty($request->get('keyWord'))){
            $categories = $categories->where('name','like','%'.$request->get('keyWord').'%');
        }
        $categories = $categories->paginate(10);

        return view('admin.category.list',compact('categories'));
        
    }
    public function create(){

        return view('admin.category.create');

    }
    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            'name' => 'required|',
            'slug' => 'required|unique:categories'
        ]);

        if($validator->passes()){

            $categories = new Category();
            $categories->name = $request->name;
            $categories->slug = $request->slug;
            $categories->status = $request->status;
            $categories->save();

            //save image here

            if(!empty($request->image_id)){
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);

                $newName = $categories->id.'.'.$ext;

                $sPath = public_path().'/temp'.'/'.$tempImage->name;
                $dPath = public_path().'/uploads/category/'.$newName;
                File::copy($sPath,$dPath);

                //genarate image thumbnail
                $destPath = public_path().'/uploads/category/thumb/'.$newName;
                $img = Image::make($sPath);
                $img->resize(450, 600);
                $img->save($destPath);

                $categories->image = $newName;
                $categories->save();
            }

            

            session()->flash('success','category added successfuly');
            return response()->json([
                'status' => true,
                'message' => 'category added successfuly'
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

    }
    public function edit(){

    }
    public function update(){

    }
    public function distroy(){

    }

    // public function getSlug(Request $request){

    //     $slug = '';
    //     if(!empty($request->title)){

    //         $slug = Str::slug($request->title);
    //     }
    //     return response()->json([
    //         'status' => true,
    //         'slug' =>  $slug
    //     ]);

    // }
}
