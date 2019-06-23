<?php

use Faker\Generator as Faker;

$factory->define(App\Drug::class, function (Faker $faker) {
    return [
        'name' => ucfirst($faker->unique()->word),
        'form' => ucfirst($faker->unique()->word),
        'strength' => ucfirst($faker->unique()->word),
        'uom' => ucfirst($faker->unique()->word),
        'price' => $faker->numberBetween(100, 100000),
    ];
});
