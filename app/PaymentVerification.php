<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentVerification extends Model
{
    protected $table = 'verification_payments';

    public function adverts()
    {
        return $this->hasOne(EthioAdvertPost::class,'id','post_id');
    }

    public function paymentMethod()
    {
     return $this->hasOne(listOfPaymentMethod::class,'id','payment_method_code');
    }

    public function user()
    {
        return $this->hasOne(User::class,'chat_id','advertiser_id');
    }
}
