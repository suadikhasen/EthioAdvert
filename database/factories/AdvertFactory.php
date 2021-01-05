<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\EthioAdvertPost;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(EthioAdvertPost::class, function (Faker $faker) {
    return [

        'advertiser_id'  => 960719750,
        'text_message'   => $faker->sentence(5),
        'image_path'    => null,
        'et_calandar_initial_date' => $faker->date(),
        'et_calendar_final_date' => $faker->date(),
        'no_view'  => null,
        'active_status' => 1,
        'approve_status' => 1,
        'payment_status' => 1 ,
        'name_of_the_advert' => $faker->sentence(2),
        'description_of_advert'  => $faker->sentence(2),
        'payment_per_view'   => null,
        'amount_of_payment'   => 1000,
        'payment_code'  => null,
        'gc_calendar_initial_date'   => Carbon::today(),
        'gc_calendar_final_date'  => Carbon::tomorrow(),
        'package_id'  => 2,
        'number_of_channel' => 5,
        're_order_status' => 0,
        'one_package_price'  => 200,
    ];
});
