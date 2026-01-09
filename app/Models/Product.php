<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_name',
        'product_description',
        'product_price',
        'product_image',
        'product_category',
        'product_company',
        'created_by'
    ];
}
