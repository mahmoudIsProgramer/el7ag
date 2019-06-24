<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;

class UserVendor extends Authenticatable
{
    use LaratrustUserTrait;
    use Notifiable;
    use \Dimsav\Translatable\Translatable;

    public $translatedAttributes = ['name'];

    protected $guarded =[];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getFirstNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function getImagePathAttribute()
    {
        return asset('public/upload/userVendor/'.$this->image);

    }

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id','id');
    }
}
