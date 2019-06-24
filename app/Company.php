<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;

class Company extends Authenticatable
{
    use LaratrustUserTrait;
    use Notifiable;
    use \Dimsav\Translatable\Translatable;

    public $translatedAttributes = ['name'];

    protected $guarded =[];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function company()
    {
        return $this->hasMany(CompanyTranslation::class ,'company_id','id');
    }

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function getImagePathAttribute()
    {
        return asset('public/upload/company/'.$this->image);

    }

    public function userVendor()
    {
        return $this->hasMany(UserVendor::class,'company_id','id');
    }
}
