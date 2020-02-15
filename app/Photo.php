<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model 
{

    protected $table = 'photos';
    public $timestamps = true;
    protected $hidden  = ['path', 'created_at', 'updated_at', 'product_id'];
    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        $url = url('public/uploads/products/'.$this->path);
        return $url;
    }
}