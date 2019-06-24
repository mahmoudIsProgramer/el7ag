<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use \Dimsav\Translatable\Translatable;
    public $translatedAttributes = ['name'];
    protected $guarded =[];

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

}
