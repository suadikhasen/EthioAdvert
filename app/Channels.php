<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channels extends Model
{
     protected $guarded = [];
     protected $primaryKey = 'channel_id';
     public    $incrementing = false;
     protected $table = 'channels';
    
     public function level()
     {
          return $this->hasOne(LevelOfChaannel::class,'id','level_id');
     }

}
