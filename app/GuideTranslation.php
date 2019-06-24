<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuideTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }
}
