<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductRate extends Model 
{

    protected $table = 'products_ratings';
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

}