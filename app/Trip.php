<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use \Dimsav\Translatable\Translatable;

    public $translatedAttributes = ['name'];

    protected $guarded =[];

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function getImagePathAttribute()
    {
        return asset('public/upload/trip/'.$this->image);

    }
    // public function getStartDAteAttribute($value)
    // {
    //     return  date('y/m/d H:i:s' , strtotime($value));
    // }

    // public function getEndDAteAttribute($value)
    // {
    //     return  date('y/m/d H:i:s' , strtotime($value));
    // }



}
