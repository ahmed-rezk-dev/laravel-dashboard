<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopPro extends Model
{

    protected $table   = 'shops_products';
    public $timestamps = true;

    public function shop()
    {
        return $this->belongsTo('App\Shop');
    }

}
