<?php

use App\Models\Category;

function getCategories(){

    return Category::orderBy('name','ASC')
                    ->with('get_sub_category')
                    ->where('status',1)
                    ->get();
}