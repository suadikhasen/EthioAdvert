<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Paid;
use Faker\Generator as Faker;

$factory->define(Paid::class, function (Faker $faker) {
    return [
        'user_id'      => 3040304,
        'paid_amount'  => 1000,
        'payment_method_name'  => $faker->name,
        'identification_number' => $faker->bankAccountNumber,
        'payment_holder_name'   => $faker->name,
    ];
});
