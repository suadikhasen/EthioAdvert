<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\LevelAttribute;
use Faker\Generator as Faker;

$factory->define(LevelAttribute::class, function (Faker $faker) {
    return [
        'attributes_name'             => $faker->name,
        'attribute_maximum_value'    => 20,
        'attribute_percentage_value' => 20,
    ];
});
