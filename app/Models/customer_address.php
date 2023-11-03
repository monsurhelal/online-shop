<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customer_address extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','fast_name','last_name','email','mobile','country_id','address','aparmemt','city','status','zip'];
    protected $table = 'customer_addresses';
}
