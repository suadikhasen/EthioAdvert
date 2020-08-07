<?php


namespace App\TelgramBot\Database;


use App\PaymentVerification;

class PaymentVerificationRepository
{
    public static function checkExistenceOfRefNumber($ref_number,$advertiser_id)
    {
        return PaymentVerification::where('advertiser_id',$advertiser_id)
            ->where('ref_number',$ref_number)
            ->where('used_status',false)
            ->exists();
    }

    public static function getPayment(int $advertiser_id, string $ref_number )
    {
        return PaymentVerification::where('advertiser_id',$advertiser_id)
            ->where('ref_number',$ref_number)
            ->where('used_status',false)
            ->first();
    }

}
