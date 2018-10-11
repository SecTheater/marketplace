<?php

namespace SecTheater\Marketplace\Providers;

use Illuminate\Support\ServiceProvider;

class EloquentEventServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap services.
	 *
	 * @return void
	 */
	public function boot() {
		if (config('market.observers.register')) {
			\SecTheater\Marketplace\Models\EloquentProduct::observe(\SecTheater\Marketplace\Observers\ProductObserver::class);
			\SecTheater\Marketplace\Models\EloquentCoupon::observe(\SecTheater\Marketplace\Observers\CouponObserver::class);

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
