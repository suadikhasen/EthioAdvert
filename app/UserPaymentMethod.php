<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPaymentMethod extends Model
{
    /**
     * @var string
     */
    protected $table = 'users_payment_method';
    /**
     * @var string
     */
    protected $primaryKey = 'chat_id';
    /**
     * @var array
     */
    protected $guarded = [];
    /**
     * @var bool
     */
    public    $incrementing = false;

    public function bank()
    {
        return $this->hasOne(BankAccount::class,'id','bank_code');
    }
}
