<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sub_categories extends Model
{
    use HasFactory;

    protected $fillable = ['name','slug','status','category_id'];
    protected $table = 'sub_categories';
}
