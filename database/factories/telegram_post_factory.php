<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\TelegramPost;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(TelegramPost::class, function (Faker $faker) {
    return [
        'message_id' => $faker->numberBetween(12324344,648364),
        'channel_id' => 1043702,
        'number_of_view' => 0,
        'earning' => 200,
        'ethio_advert_post_id' => 2,
        'active_status' => 1,
        'channel_owner_id' => 3040304,
        'created_at' => Carbon::create(2020,2,3)
    ];
});
