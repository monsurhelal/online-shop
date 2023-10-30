<?php

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\brandsController;
use App\Http\Controllers\admin\categoryController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\productsController;
use App\Http\Controllers\admin\ProductSubCategoryControlller;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\TempImageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\frontController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [frontController::class,'index'])->name('home.index');
Route::get('/shop/{category?}/{subCategory?}', [ShopController::class,'index'])->name('shop.index');
Route::get('/product/{slug}', [ShopController::class,'product'])->name('shop.product');
Route::get('/cart', [CartController::class,'cart'])->name('shop.cart');
Route::post('/add-to-cart', [CartController::class,'AddToCart'])->name('shop.AddToCart');
Route::post('/update-cart', [CartController::class,'updateCart'])->name('shop.updateCart');
Route::post('/delete-item', [CartController::class,'deleteItem'])->name('shop.deleteItem');
Route::get('/checkout', [CartController::class,'checkout'])->name('shop.checkout');



Route::group(['prefix'=>'account'],function(){
    Route::group(['middleware'=>'guest'],function(){
        Route::get('/registation',[AuthController::class,'registation'])->name('user.registation');
        Route::post('/registation/user',[AuthController::class,'registationUser'])->name('user.registationUser');
        Route::get('/login',[AuthController::class,'login'])->name('user.login');
        Route::post('/login',[AuthController::class,'authenticate'])->name('user.authenticate');
    });
    Route::group(['middleware'=>'auth'],function(){
        Route::get('/profile',[AuthController::class,'profile'])->name('user.profile');
        Route::get('/logout',[AuthController::class,'logout'])->name('user.logout');
    });

});


Route::group(['prefix'=>'admin'],function(){

    Route::group(['middleware'=>'admin.guest'],function(){
        Route::get('/login', [AdminLoginController::class,'index'])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class,'authenticate'])->name('admin.authenticate');
    });
    Route::group(['middleware'=>'admin.auth'],function(){

        Route::get('/deshboard', [HomeController::class,'index'])->name('admin.deshboard');
        Route::get('/logout', [HomeController::class,'logout'])->name('admin.logout');
        //category route here


        Route::get('/categories', [categoryController::class,'index'])->name('categories.index');
        Route::get('/categories/create', [categoryController::class,'create'])->name('categories.create');
        Route::post('/categories/store', [categoryController::class,'store'])->name('categories.store');

        //sub category route here
        Route::get('/sub-categories', [SubCategoryController::class,'index'])->name('sub-categories.index');
        Route::get('/sub-categories/create', [SubCategoryController::class,'create'])->name('sub-categories.create');
        Route::post('/sub-categories/store', [SubCategoryController::class,'store'])->name('sub-categories.store');

        //brands route here

        Route::get('/brands', [brandsController::class,'index'])->name('brands.index');
        Route::get('/brands/create', [brandsController::class,'create'])->name('brands.create');
        Route::post('/brands/store', [brandsController::class,'store'])->name('brands.store');
        Route::get('/brands/{brand}/edit', [brandsController::class,'edit'])->name('brands.edit');
        Route::put('/brands/{brand}', [brandsController::class,'update'])->name('brands.update');
        Route::delete('/brands/{brand}', [brandsController::class,'delete'])->name('brands.delete');

        //products route here
        Route::get('/products', [productsController::class,'index'])->name('products.index');
        Route::get('/products/create', [productsController::class,'create'])->name('products.create');
        Route::post('/products/store', [productsController::class,'store'])->name('products.store');


        Route::get('/product_sub_categories', [ProductSubCategoryControlller::class,'index'])->name('product_sub_categories.index');


        //temp image route here
        Route::post('/temp-upload-image', [TempImageController::class,'create'])->name('temp-images.create');


        Route::get('/getSlug', function(Request $request){
            
        $slug = '';

        if(!empty($request->title)){

            $slug = str()->slug($request->title);
        }
        return response()->json([
            'status' => true,
            'slug' =>  $slug
        ]);
        })->name('getSlug');

    });

});
