<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaintAucts extends Model 
{

    protected $table = 'maintenance__auctions';
    public $timestamps = true;

    public function shop()
    {
        return $this->belongsTo('App\Shop', 'shop_id');
    }

}