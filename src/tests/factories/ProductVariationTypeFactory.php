<?php

use Faker\Generator as Faker;
use SecTheater\Marketplace\Models\EloquentProductVariationType;

$factory->define(EloquentProductVariationType::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'stock' => $faker->numberBetween(3,50)

    ];
});
