<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Category extends Model
{

    protected $table   = 'categories';
    public $timestamps = true;

    protected $appends = ['name', 'logo_url'];
    protected $hidden  = ['name_ar', 'name_en', 'logo'];

    public function User()
    {
        return $this->hasOne('App\User', 'user_id');
    }

    public function subCategory()
    {
        return $this->hasMany('App\SubCategory', 'category_id');
    }

    public function getNameAttribute()
    {
        $name = 'name_' . App::getLocale();
        return $this->attributes[$name];
    }

    public function getLogoUrlAttribute()
    {
        $logo_url = url('public/uploads/logos/' . $this->logo);
        if (!empty($this->logo)) {
            return $logo_url;
        }
    }
}
