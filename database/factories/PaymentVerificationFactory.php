<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\PaymentVerification;
use Faker\Generator as Faker;

$factory->define(PaymentVerification::class, function (Faker $faker) {
    return [
        'payment_method_code' => 1,
        'advertiser_id'  => 960719750,
        'amount'        => 1000,
        'ref_number'   => $faker->uuid,
        'used_status'  => 1,
        'post_id'      => 5
    ];
});
