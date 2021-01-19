<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionNumbers extends Model
{
    protected $table = 'transaction_number';
    protected $guarded = [];

    public function paymentMethod()
    {
        return $this->hasOne(listOfPaymentMethod::class,'id','payment_method_code');
    }
}
