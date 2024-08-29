<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'subcategories';
    use HasFactory;

    // public function category(){
    //     return $this->hasMany('App\Models\Category','category_id','id');
    // }
}
