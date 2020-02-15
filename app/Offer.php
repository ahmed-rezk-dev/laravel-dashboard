<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Offer extends Model 
{

    protected $table = 'offers';
    public $timestamps = true;
    protected $appends = ['image_url'];
    protected $hidden  = ['created_at', 'updated_at', 'image_ar', 'image_en'];

    public function getImageUrlAttribute()
    {
    	$image = $this['image_'.App::getLocale()];

        $url = url('public/uploads/offers/'.$image);

        if (!empty($image)) {
	        return $url;
        }
    }
}