<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupervisorTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];

    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }
}
