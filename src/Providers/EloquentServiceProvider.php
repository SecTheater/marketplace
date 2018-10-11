<?php

namespace SecTheater\Marketplace\Providers;

use Illuminate\Support\ServiceProvider;
use SecTheater\Marketplace\Models\EloquentCart as Cart;
use SecTheater\Marketplace\Models\EloquentCategory as Category;
use SecTheater\Marketplace\Models\EloquentCoupon as Coupon;
use SecTheater\Marketplace\Models\EloquentProduct as Product;
use SecTheater\Marketplace\Models\EloquentProductVariation as ProductVariation;
use SecTheater\Marketplace\Models\EloquentProductVariationType as ProductVariationType;
use SecTheater\Marketplace\Models\EloquentRole;
use SecTheater\Marketplace\Models\EloquentSale as Sale;
use SecTheater\Marketplace\Models\EloquentUser as User;
use SecTheater\Marketplace\Models\EloquentWishlist as Wishlist;
use SecTheater\Marketplace\Repositories\CartRepository;
use SecTheater\Marketplace\Repositories\CategoryRepository;
use SecTheater\Marketplace\Repositories\CouponRepository;
use SecTheater\Marketplace\Repositories\ProductRepository;
use SecTheater\Marketplace\Repositories\ProductVariationRepository;
use SecTheater\Marketplace\Repositories\ProductVariationTypeRepository;
use SecTheater\Marketplace\Repositories\SaleRepository;
use SecTheater\Marketplace\Repositories\RoleRepository;
use SecTheater\Marketplace\Repositories\UserRepository;
use SecTheater\Marketplace\Repositories\WishlistRepository;

class EloquentServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot() {
		//
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		$this->app->singleton('ProductVariationRepository', function () {
			return new ProductVariationRepository(new ProductVariation);
		});

		$this->app->singleton('ProductVariationTypeRepository', function () {
			return new ProductVariationTypeRepository(new ProductVariationType);
		});

		$this->app->singleton('CouponRepository', function () {
			return new CouponRepository(new Coupon);
		});

		$this->app->singleton('WishlistRepository', function () {
			return new WishlistRepository(new Wishlist);
		});

		$this->app->singleton('CategoryRepository', function () {
			return new CategoryRepository(new Category);
		});

		$this->app->singleton('UserRepository', function () {
			return new UserRepository(new User);
		});

		$this->app->singleton('SaleRepository', function () {
			return new SaleRepository(new Sale);
		});
		$this->app->singleton('RoleRepository', function () {
			return new RoleRepository(new EloquentRole);
		});

		$this->app->singleton('ProductRepository', function () {
			return new ProductRepository(new Product);
		});

		$this->app->singleton('CartRepository', function () {
			return new CartRepository(new Cart);
		});
	}
}
