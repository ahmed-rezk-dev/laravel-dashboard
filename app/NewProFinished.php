<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewProFinished extends Model
{

    protected $table   = 'new_products_finished';
    public $timestamps = true;

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
    function new () {
        return $this->belongsTo('App\NewPro', 'new_id');
    }

    public function shop()
    {
        return $this->belongsTo('App\Shop');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
