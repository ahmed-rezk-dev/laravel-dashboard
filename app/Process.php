<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Process extends Model
{

    protected $table   = 'processing';
    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function shop()
    {
        return $this->belongsTo('App\Shop');
    }

    public function newProduct()
    {
        return $this->belongsTo('App\NewPro', 'new_id');
    }

    public function maintenance()
    {
        return $this->belongsTo('App\Maint', 'maint_id');
    }

}
