<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\TelegramPost;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(TelegramPost::class, function (Faker $faker) {
    return [
        'message_id'           =>   $faker->numberBetween(1,2000),
        'channel_id'           =>   5530143,
        'number_of_view'       =>   100,
        'earning'              =>   200,
        'ethio_advert_post_id' =>   32,
        'channel_owner_id'     =>   1813873,
        'created_at'           =>   Carbon::now()->subMonth(),
        'updated_at'           =>   Carbon::now()->subMonth(),
    ];
});
