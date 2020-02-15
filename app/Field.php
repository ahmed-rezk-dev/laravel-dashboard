<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Field extends Model 
{

    protected $table = 'Fields';
    public $timestamps = true;

    protected $appends = ['name', 'vaule'];
    protected $hidden  = ['product_id', 'name_ar', 'name_en', 'vaule_ar', 'vaule_en', 'updated_at',  'created_at'];

    public function getNameAttribute()
    {
        $name = 'name_'.App::getLocale();
        return $this->attributes[$name];
    }

    public function getVauleAttribute()
    {
        $vaule = 'vaule_'.App::getLocale();
        return $this->attributes[$vaule];
    }
}