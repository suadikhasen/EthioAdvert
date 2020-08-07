<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TelegramPost extends Model
{
    /**
     * @var string
     */
    protected $table = 'telegram_posts';
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     *
     */
    public function channelsName()
    {
        return $this->hasOne(Channels::class,'channel_id','channel_id');
    }

    public  function adverts()
    {
        return $this->hasOne(EthioAdvertPost::class,'id','ethio_advert_post_id');
    }
}
