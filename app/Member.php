<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;

class Member extends Authenticatable
{
    use LaratrustUserTrait;
    use Notifiable;
    use \Dimsav\Translatable\Translatable;

    public $translatedAttributes = ['name'];

    protected $guarded =[];
    public $timestamps = false;

    protected $hidden = [
        'password', 'remember_token',
    ];
    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function getImagePathAttribute()
    {
        return asset('public/upload/member/'.$this->image);

    }
}
