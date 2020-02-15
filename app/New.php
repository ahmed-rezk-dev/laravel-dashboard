<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class New extends Model 
{

    protected $table = 'new_product';
    public $timestamps = true;

    public function User()
    {
        return $this->belongsTo('App\User');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function auctions()
    {
        return $this->hasMany('App\ProAucts', 'new_id');
    }

}