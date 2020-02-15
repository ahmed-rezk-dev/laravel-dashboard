<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaintSubCats extends Model 
{

    protected $table = 'maintenance_sub_categories';
    public $timestamps = true;

    public function category()
    {
        return $this->belongsTo('App\MaintCats');
    }

    public function childs()
    {
        return $this->hasMany('App\MaintSubCats', 'father_id');
    }

}