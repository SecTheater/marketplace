<?php

namespace SecTheater\Marketplace\Providers;

use SecTheater\Marketplace\Observers\CouponObserver;
use SecTheater\Marketplace\ProductObserver;
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
		Product::observe(ProductObserver::class);
		Coupon::observe(CouponObserver::class);
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
