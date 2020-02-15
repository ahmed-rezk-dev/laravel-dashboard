<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopCat extends Model
{

    protected $table   = 'shop_categories';
    public $timestamps = true;
    protected $guarded = [];
    protected $hidden  = ['created_at', 'updated_at'];
    protected $casts   = [
        'subCategory_id' => 'int',
    ];

    public function shop()
    {
        return $this->belongsTo('App\Shop');
    }

    public function subCategory()
    {
        return $this->belongsTo('App\SubCategory', 'subCategory_id');
    }

}
