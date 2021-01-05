<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Paid extends Model
{
    protected  $table ='paids';
    protected  $primaryKey = 'transaction_id';
    public     $incrementing = false;
    protected  $casts =[
        'created_at'  => 'date'
    ];
    protected $guarded = [];
//    protected $dateFormat = 'U';
    public function paymentMethod()
    {

    }
}
