<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Maint extends Model
{

    protected $table   = 'maintenance';
    public $timestamps = true;
    protected $appends = ['image_url'];
    protected $hidden  = ['image'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function subCategory()
    {
        return $this->belongsTo('App\SubCategory', 'subCategory_id');
    }

    public function auctions()
    {
        return $this->hasMany('App\MaintAucts', 'maint_id');
    }

    public function processe()
    {
        return $this->hasOne('App\Process', 'maint_id');
    }

    public function finished()
    {
        return $this->hasOne('App\MaintFinished', 'maint_id');
    }

    // maintenances image
    public function getImageUrlAttribute()
    {
        $url = url('public/uploads/maintenances/' . $this->image);
        if ($this->image) {
            return $url;
        }
        return '';
    }

}
