<?php

namespace SecTheater\Marketplace\Providers;

use SecTheater\Marketplace\Observers\CouponObserver;
use SecTheater\Marketplace\ProductObserver;
use Illuminate\Support\ServiceProvider;
use SecTheater\Marketplace\Models\EloquentCoupon;
use SecTheater\Marketplace\Models\EloquentProduct;

class EloquentEventServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot() {
		if (config('market.observers.register')) {
			EloquentProduct::observe(ProductObserver::class);
			EloquentCoupon::observe(CouponObserver::class);
		}
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
