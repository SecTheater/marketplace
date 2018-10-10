<?php

namespace SecTheater\Marketplace\Providers;

use Illuminate\Support\ServiceProvider;
use SecTheater\Marketplace\Models\EloquentCoupon as Coupon;
use SecTheater\Marketplace\Models\EloquentProduct as Product;

class EloquentEventServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot() {
		Product::observe(\App\Observers\ProductObserver::class);
		Coupon::observe(\App\Observers\CouponObserver::class);
	}

	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register() {
		//
	}
}
