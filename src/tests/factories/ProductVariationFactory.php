<?php

use Faker\Generator as Faker;
use SecTheater\Marketplace\Models\EloquentProduct;
use SecTheater\Marketplace\Models\EloquentProductVariation;

$factory->define(EloquentProductVariation::class, function (Faker $faker) {
	return [
		'product_id' => factory(EloquentProduct::class)->create()->id,
		'details' => ['color' => 'blue', 'size' => 'XL'],
	];
});
