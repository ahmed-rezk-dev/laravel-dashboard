<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopMaint extends Model
{

    protected $table   = 'shop_maintenance';
    public $timestamps = true;
    protected $guarded = [];
    protected $hidden  = ['created_at', 'updated_at'];
    protected $casts   = [
        'category_id' => 'int',
    ];

    public function shop()
    {
        return $this->belongsTo('App\Shop');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

}
