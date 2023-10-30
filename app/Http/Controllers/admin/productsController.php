<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\brads;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\Products;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Image;

class productsController extends Controller
{
    public function index() {

        $products = Products::latest('id')->with('product_images')->paginate(10);
        return view('admin.products.list',compact('products'));
        
    }
    public function create(){

        $categories = Category::orderBy('name','ASC')->get();
        $brands = brads::orderBy('name','ASC')->get();
        return view('admin.products.create',compact(['categories','brands']));
    }

    public function store(Request $request){

        $rules = [
            'title' => 'required',
            'slug' => 'required',
            'price' => 'required|numeric',
            'sku' => 'required',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
 
        ];

        if(!empty($request->track_qty) && $request->track_qty == 'Yes'){
            $rules['qty'] = 'required|numeric';
        }

       $validator =  Validator::make($request->all(),$rules);

       if($validator->passes()){

        $product = new Products();
        $product->title = $request->title;
        $product->slug = $request->slug;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->compare_price = $request->compare_price;
        $product->categories_id = $request->category;
        $product->sub_categories_id = $request->sub_category;
        $product->brads_id = $request->brand;
        $product->is_featured = $request->is_featured;
        $product->sku = $request->sku;
        $product->barcode = $request->barcode;
        $product->track_qty = $request->track_qty;
        $product->qty = $request->qty;
        $product->status = $request->status;

        $product->save();

        //save image pics

        if(!empty($request->image_array)){

            foreach($request->image_array as $temp_image_id){


                

                $productImage = new ProductImage();
                $productImage->products_id = $product->id;
                $productImage->image = "NULL";
                $productImage->save();

                $tampImageInfo = TempImage::find($temp_image_id);
                $extArray = explode('.',$tampImageInfo->name);
                $ext = last($extArray);

                $imageName = $product->id.'-'.$productImage->id.'-'.time().'.'.$ext;
                $productImage->image = $imageName;
                $productImage->save();


                //genarate image thamnail

                //large image

                $soursePath = public_path().'/temp'.'/'.$tampImageInfo->name;
                $destPath = public_path().'/uploads/products/large/'.$imageName;
                $image = Image::make($soursePath);
                $image->resize(1400, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $image->save($destPath);

                //small image

                $destPath = public_path().'/uploads/products/small/'.$imageName;
                $image = Image::make($soursePath);
                $image->fit(300,300);
                $image->save($destPath);

            }

            

        }

        session()->flash('success','product add successfuly');

        return response()->json([
            'status' => true,
            'success' => 'product add successfuly'
        ]);


       }else{

        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ]);
       }

    }
}
