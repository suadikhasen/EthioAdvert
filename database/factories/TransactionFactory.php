<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\TransactionNumbers;
use Faker\Generator as Faker;

$factory->define(TransactionNumbers::class, function (Faker $faker) {
    return [
        'ref_number'   => $faker->bankAccountNumber,
        'payment_method_code' => 1,
    ];
});
