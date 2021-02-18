<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class EthioAdvertPost extends Model
{
    protected $table ='ethio_advert_posts';
    protected $guarded = [];
    protected $dates = [
        'initial_date',
        'final_date'
    ];
    public $appends = ['is_posted'];


    protected $casts = [
        "assigned_channels"        => "array",
        'gc_calendar_initial_date' => 'D:F:Y',
        'gc_calendar_final_date'   => 'D:F:Y',
        'initial_time'             => 'H:i',
        'final_time'               => 'H:i',
   ];

    public function  package()
    {
        return $this->hasOne(Package::class,'id','package_id');
    }

    public function getIsPostedtAttribute()
    {
      return  TelegramPost::whereDate('created_at',Carbon::today())
        ->where('ethio_advert_post_id',$this->id)
        ->exists();
    }
}
