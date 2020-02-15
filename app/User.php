<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $appends = ['avatar_url'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'avatar'
    ];

    public function Role()
    {
        return $this->hasOne('App\Role','id','role');
    }

    public function Reports()
    {
        return $this->hasMany('App\Report','user_id','id');
    }

    public function shop()
    {
        return $this->hasOne('App\Shop', 'user_id');
    }

    public function settings()
    {
        return $this->hasOne('App\UserSettings', 'user_id');
    }

    public function products()
    {
        return $this->hasMany('App\Product', 'user_id');
    }

    public function basket()
    {
        return $this->hasMany('App\Basket', 'user_id');
    }

    public function maintenance()
    {
        return $this->hasMany('App\Maint', 'user_id');
    }

    public function newProducts()
    {
        return $this->hasMany('App\NewPro', 'user_id');
    }

    public function analysis()
    {
        return $this->hasOne('App\UserAna', 'user_id');
    }

    public function getAvatarUrlAttribute()
    {
        $url = url('public/uploads/avatars/'.$this->avatar);
        if ($this->avatar) {
            return $url;
        }
        return '';
    }

}
