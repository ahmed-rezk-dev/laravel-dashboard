<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class SubCategory extends Model
{

    protected $table   = 'SubCategories';
    public $timestamps = true;
    protected $appends = ['name'];
    protected $hidden  = ['name_ar', 'name_en'];

    public function User()
    {
        return $this->hasOne('App\User', 'user_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Category', 'category_id');
    }

    public function father()
    {
        return $this->belongsTo('App\SubCategory', 'father_id');
    }

    public function products()
    {
        return $this->hasMany('App\Product', 'subCategory_id');
    }

    public function childs()
    {
        $getChilds = $this->hasMany('App\SubCategory', 'father_id');
        return $getChilds->whereHas('products');
    }

    public function newMaint()
    {
        return $this->hasMany('App\Maint', 'subCategory_id');
    }

    public function getNameAttribute()
    {
        $name = 'name_' . App::getLocale();
        return $this->attributes[$name];
    }

    public function getChildsAttribute()
    {
        if ($this->child != null) {
            return $this->child;
        }
        return [];
    }

}
