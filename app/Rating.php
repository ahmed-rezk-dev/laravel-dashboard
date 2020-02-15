<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model 
{

    protected $table = 'ratings';
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