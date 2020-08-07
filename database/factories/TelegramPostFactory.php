<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\TelegramPost;
use Faker\Generator as Faker;

$factory->define(TelegramPost::class, function (Faker $faker) {
    return [
        'message_id'           =>   $faker->numberBetween(1,2000),
        'channel_id'           =>   -1001281861078,
        'number_of_view'       =>   100,
        'earning'              =>   1000,
        'ethio_advert_post_id' =>   $faker->numberBetween(1,2000),
        'channel_owner_id'     =>   960719750

    ];
});
