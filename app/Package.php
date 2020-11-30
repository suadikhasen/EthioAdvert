<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\LevelOfChaannel;
class Package extends Model
{
    //

    public function level()
    {
        return $this->hasOne(LevelOfChaannel::class,'id','channel_level_id');
    }
}
