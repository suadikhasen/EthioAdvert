<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\BankAccount;
use Faker\Generator as Faker;

$factory->define(BankAccount::class, function (Faker $faker) {
    return [
        'bank_name'            => $faker->name,
        'account_number'       => $faker->bankAccountNumber,
        'account_holder_name'  => $faker->name,
    ];
});
