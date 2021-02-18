<?php


namespace App\TelgramBot\Database;


use App\PaymentVerification;
use App\TransactionNumbers;

class PaymentVerificationRepository
{
    public static function checkExistenceOfRefNumber($ref_number,$advertiser_id)
    {
        return PaymentVerification::where('advertiser_id',$advertiser_id)
            ->where('ref_number',$ref_number)
            ->where('used_status',false)
            ->exists();
    }

    public static function isRefAvailable($ref_number ,$payment_code)
    {
        return TransactionNumbers::where('ref_number',$ref_number)
            ->where('used_status',false)
            ->where('payment_method_code',$payment_code)
            ->first();
    }

}
