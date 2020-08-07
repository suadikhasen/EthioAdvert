<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Paid;
use Faker\Generator as Faker;

$factory->define(Paid::class, function (Faker $faker) {
    return [
        'user_id'      => 960719750,
        'paid_amount'  => 100
    ];
});
