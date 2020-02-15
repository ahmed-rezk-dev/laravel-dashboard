<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopRate extends Model 
{

    protected $table = 'shops_rating';
    public $timestamps = true;

    public function shop()
    {
        return $this->hasOne('App\Shop');
    }

    public function user()
    {
        return $this->hasOne('App\User');
    }

}