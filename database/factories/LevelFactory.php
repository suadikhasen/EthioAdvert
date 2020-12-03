<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\LevelOfChaannel;
use Faker\Generator as Faker;

$factory->define(LevelOfChaannel::class, function (Faker $faker) {
    return [
        'level_name' => $faker->firstName(),
        'level'      => $faker->numberBetween(1,5)
    ];
});
