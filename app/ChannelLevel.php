<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChannelLevel extends Model
{
    protected $table = 'chanel_level';

    protected  $guarded = [];

    public function Channels()
    {
        return $this->hasMany(Channels::class,'level_id','level');
    }

    public $appends = ['number_of_channels'];

    public function getNumberOfChannelsAttribute()
    {
      return Channels::where('level_id',$this->id)->count();
    }
    
}
