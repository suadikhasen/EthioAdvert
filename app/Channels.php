<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Channels extends Model
{
     protected $guarded = [];
     protected $primaryKey = 'channel_id';
     public    $incrementing = false;
     protected $table = 'channels';
     public $appends = ['number_of_posts'];
    
     public function level()
     {
          return $this->hasOne(LevelOfChaannel::class,'id','level_id');
     }

     public  function user()
     {
          return $this->hasOne(User::class,'chat_id','id');
     }

     public function getNumberOfPostsAttributes()
     {
          return DB::table('telegram_posts')->where('channel_id',$this->channel_id)->select('ethio_advert_post_id')->distinct()->count();
     }

}
