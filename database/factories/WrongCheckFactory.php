<?php

use Faker\Generator as Faker;

$factory->define(App\WrongCheck::class, function (Faker $faker) {
    return [
        'drug_name' => ucfirst($faker->unique()->word),
        'buying_amount' => $faker->randomNumber,
    ];
});
