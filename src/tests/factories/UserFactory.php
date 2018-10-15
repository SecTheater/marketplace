<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
 */

$factory->define(\SecTheater\Marketplace\Models\EloquentUser::class, function (Faker $faker) {
	static $password;
	return [
		'username' => $faker->unique()->userName,
		'email' => $faker->unique()->safeEmail,
		'password' => $password ?: $password = bcrypt(123456789),
		'location' => 'Egypt',
		'remember_token' => str_random(10),
	];
});
$factory->define(SecTheater\Marketplace\Models\EloquentCategory::class, function (Faker $faker) {
	return [
		'type' => $faker->unique()->word,
	];
});
$factory->define(\SecTheater\Marketplace\Models\EloquentProduct::class, function (Faker $faker) {
	return [
		'user_id' =>  function () {
            return factory(\SecTheater\Marketplace\Models\EloquentUser::class)->create()->id;
        },
		'description' => $faker->paragraph,
		'price' => $faker->numberBetween(10, 100),
		'name' => $faker->unique()->word,
		'photo' => $faker->imageUrl(640, 480, 'fashion'),
		'reviewed_at' => Carbon::now()->format('Y-m-d H:i:s'),
	];
});
$factory->define(\SecTheater\Marketplace\Models\EloquentCart::class, function (Faker $faker) {
	$products_id = \DB::table('products')->select('id')->get()->toArray();
	return [
		'product_id' => $faker->randomElement($products_id)->id,
		'quantity' => $faker->numberBetween(1, 10),
	];
});
$factory->define(\SecTheater\Marketplace\Models\EloquentWishlist::class, function (Faker $faker) {
	$products_id = \DB::table('products')->select('id')->get()->toArray();
	return [
		'product_id' => $faker->randomElement($products_id)->id,
		'quantity' => $faker->numberBetween(1, 10),
	];
});
$factory->define(\SecTheater\Marketplace\Models\EloquentCoupon::class, function (Faker $faker) {
	return [
		'code' => str_random(32),
		'amount' => $faker->numberBetween(10, 100),
		'active' => $faker->boolean,
		'expires_at' => \Carbon\Carbon::now()->addDays(7)->format('Y-m-d H:i:s'),
		'percentage' => $faker->numberBetween(10, 100),
	];
});
