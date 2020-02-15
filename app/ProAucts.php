<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProAucts extends Model 
{

    protected $table = 'product_auctions';
    public $timestamps = true;

    public function shop()
    {
        return $this->belongsTo('App\Shop', 'shop_id');
    }

}