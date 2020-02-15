<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Basket extends Model 
{

    protected $table = 'basket';
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

}