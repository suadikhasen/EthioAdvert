<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\UserPaymentMethod;
use Faker\Generator as Faker;

$factory->define(UserPaymentMethod::class, function (Faker $faker) {
    return [
        'chat_id'             => 3040304,
        'bank_code'        => 1,
        'account_number'   => $faker->bankAccountNumber,
        'full_name'        =>   $faker->name,
    ];
});
