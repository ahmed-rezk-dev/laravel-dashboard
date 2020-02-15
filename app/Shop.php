<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{

    protected $table   = 'shops';
    public $timestamps = true;
    protected $guarded = [];
    protected $appends = ['commercial_img_url', 'logo_url', 'dealing_performance', 'delivery_quality', 'work_experiencek', 'dealing_again'];
    protected $hidden  = ['commercial_register_img', 'logo'];

    public function ratings()
    {
        return $this->hasMany('App\ShopRate', 'shop_id');
    }

    public function products()
    {
        return $this->hasMany('App\ShopPro');
    }

    public function newProFinished()
    {
        return $this->hasMany('App\NewProFinished', 'shop_id');
    }

    public function maintFinished()
    {
        return $this->hasMany('App\MaintFinished', 'shop_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function shopPro()
    {
        return $this->hasMany('App\ShopPro', 'shop_id');
    }

    public function processes()
    {
        return $this->hasMany('App\Process');
    }

    // commercial_register_img
    public function getCommercialImgUrlAttribute()
    {
        $url = url('public/uploads/commercial/' . $this->commercial_register_img);
        if ($this->commercial_register_img) {
            return $url;
        }
        return '';
    }

    // logo
    public function getLogoUrlAttribute()
    {
        $url = url('public/uploads/logos/' . $this->logo);
        if ($this->logo) {
            return $url;
        }
        return '';
    }

    // dealing performance
    public function getDealingPerformanceAttribute()
    {
        $rateing    = $this->ratings->count();
        $rateingSum = $this->ratings->sum('dealingPerformance');

        if ($rateing > 0) {
            $rate = round($rateing / $rateingSum, 1);
        } else {
            $rate = 0;
        }

        return $rate;
    }

    // delivery quality
    public function getDeliveryQualityAttribute()
    {
        $rateing    = $this->ratings->count();
        $rateingSum = $this->ratings->sum('deliveryQuality');

        if ($rateing > 0) {
            $rate = round($rateing / $rateingSum, 1);
        } else {
            $rate = 0;
        }

        return $rate;
    }

    // work experiencek
    public function getWorkExperiencekAttribute()
    {
        $rateing    = $this->ratings->count();
        $rateingSum = $this->ratings->sum('workExperiencek');

        if ($rateing > 0) {
            $rate = round($rateing / $rateingSum, 1);
        } else {
            $rate = 0;
        }

        return $rate;
    }

    // dealing again
    public function getDealingAgainAttribute()
    {
        $rateing    = $this->ratings->count();
        $rateingSum = $this->ratings->sum('dealingAgain');

        if ($rateing > 0) {
            $rate = round($rateing / $rateingSum, 1);
        } else {
            $rate = 0;
        }

        return $rate;
    }

    public function shopCategory()
    {
        return $this->hasMany('App\ShopCat');
    }

    public function shopMaint()
    {
        return $this->hasMany('App\ShopMaint');
    }

}
