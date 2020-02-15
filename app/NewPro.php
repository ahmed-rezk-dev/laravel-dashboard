<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewPro extends Model
{

    protected $table   = 'new_product';
    public $timestamps = true;
    // protected $fillable = [];
    protected $guarded = [];

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

    public function processe()
    {
        return $this->hasOne('App\Process', 'new_id');
    }

    public function finished()
    {
        return $this->hasOne('App\NewProFinished', 'new_id');
    }

}
