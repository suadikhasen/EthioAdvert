<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Channels;
use Faker\Generator as Faker;

$factory->define(Channels::class, function (Faker $faker) {
    return [
        'channel_id'  => $faker->numberBetween(1000000,9000000),
        'name'         => $faker->firstName(),
        'channel_owner_id' => 1813873,
        'number_of_member' => $faker->numberBetween(2000,10000),
        'approve_status' => true,
        'per_day_posts'=>5,
        'level_id' =>10,
        'username' => $faker->userName
    ];
});
