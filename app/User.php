<?php

namespace App;

use App\TelgramBot\Database\PaidRepository;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    /*protected $hidden = [
        'password', 'remember_token',
    ];*/

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected  $primaryKey = 'chat_id';
    public     $incrementing = false;
    protected  $keyType = 'unsignedBigInteger';

    public function payment_method()
    {
        return $this->hasOne(UserPaymentMethod::class,'chat_id','chat_id');
    }

    public function getPendingPaymentAttribute()
    {
        return PaidRepository::totalPendingPaymentOfUser($this->chat_id);
    }


}
