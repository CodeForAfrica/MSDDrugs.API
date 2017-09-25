<?php

use Faker\Generator as Faker;

$factory->define(App\PriceCheck::class, function (Faker $faker) {
    return [
        'drug_id' => function () {
        	return factory('App\Drug')->create()->id;
        },
        'buying_price' => $faker->randomNumber(),
        'status' => $faker->boolean,
        'extra_amount' => $faker->randomNumber(),
    ];
});
