<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaintFinished extends Model
{

    protected $table   = 'maintenances_finished';
    public $timestamps = true;

    public function shop()
    {
        return $this->belongsTo('App\Shop');
    }
}
