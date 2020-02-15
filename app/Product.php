<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Product extends Model
{

    protected $table   = 'products';
    public $timestamps = true;
    protected $appends = ['name', 'quality_rate', 'performance_rate', 'photo_url'];
    protected $hidden  = ['name_ar', 'name_en', 'updated_at'];

    public function subCategory()
    {
        return $this->belongsTo('App\SubCategory');
    }

    public function photos()
    {
        return $this->hasMany('App\Photo', 'product_id');
    }

    public function fields()
    {
        return $this->hasMany('App\Field', 'product_id');
    }

    public function ratings()
    {
        return $this->hasMany('App\ProductRate', 'product_id');
    }

    public function newProducts()
    {
        return $this->hasMany('App\NewPro');
    }

    public function getNameAttribute()
    {
        $name = 'name_' . App::getLocale();
        return $this->attributes[$name];
    }

    // Quality Rate
    public function getQualityRateAttribute()
    {
        $rateing    = $this->ratings->count();
        $rateingSum = $this->ratings->sum('quality');

        if ($rateing > 0) {
            $rate = round($rateing / $rateingSum, 1);
        } else {
            $rate = 0;
        }

        return $rate;
    }

    // Performance Rate
    public function getPerformanceRateAttribute()
    {
        $rateing    = $this->ratings->count();
        $rateingSum = $this->ratings->sum('performance');

        if ($rateing > 0) {
            $rate = round($rateing / $rateingSum, 1);
        } else {
            $rate = 0;
        }

        return $rate;
    }

    public function getphotoUrlAttribute()
    {
        return $this->photos->first()->url;
    }
}
