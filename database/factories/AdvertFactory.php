<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\EthioAdvertPost;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(EthioAdvertPost::class, function (Faker $faker) {
    return [

        'advertiser_id'  => 29305505,
        'text_message'   => $faker->sentence(5),
        'image_path'    => null,
        'no_view'  => null,
        'active_status' => 1,
        'approve_status' => 1,
        'payment_status' => 1 ,
        'name_of_the_advert' => $faker->sentence(2),
        'description_of_advert'  => $faker->sentence(2),
        'amount_of_payment'   => 1000,
        'payment_code'  => null,
        'gc_calendar_initial_date'=> Carbon::now()->subMonth(),
        'gc_calendar_final_date'  => Carbon::now()->subMonth()->addDays(4),
        'package_id'              => 4,
        'number_of_channel'       => 2,
        're_order_status'         => 0,
        'one_package_price'       => 500,
        'channel_price'           => 400,
        'assigned_channels'       => [5774287,5530143],
    ];
});
