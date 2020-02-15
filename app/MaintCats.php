<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaintCats extends Model 
{

    protected $table = 'maintenance_categories';
    public $timestamps = true;

    public function subCategories()
    {
        return $this->hasMany('App\MaintSubCats', 'category_id');
    }

}