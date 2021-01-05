<?php

namespace App;

use App\TelgramBot\Database\Admin\PaymentRepository;
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

    public function user()
    {
        return $this->hasOne(User::class,'chat_id','channel_owner_id');
    }

    public $appends = ['pending_payment'];

    public function getPendingPaymentAttribute()
    {
        return PaymentRepository::pendingPaymentOfChannelOwners($this->channel_owner_id);
    }

}
