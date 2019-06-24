<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Path extends Model
{

    protected $guarded =[];

    public function TripTranslation($id){

    $path= \App\TripTranslation::where('trip_id',$id)->get();
    foreach($path as $pa){
      return $pa->name;
    }
    
 }

}
