<?php

namespace SecTheater\Marketplace\Tests\Unit;

use SecTheater\Marketplace\Models\EloquentCart as Cart;
use SecTheater\Marketplace\Models\EloquentCategory as Category;
use SecTheater\Marketplace\Models\EloquentProduct as Product;
use SecTheater\Marketplace\Models\EloquentProductVariation as ProductVariation;
use SecTheater\Marketplace\Models\EloquentProductVariationType as ProductVariationType;
use SecTheater\Marketplace\Models\EloquentWishlist as Wishlist;


trait CartTrait {
	protected $product, $cart, $cartInstance, $productInstance, $typeRepo, $category;
	public function setUp() {
		parent::setUp();
		$this->category = factory(Category::class)->create();
		auth()->user()->cart()->saveMany(factory(Product::class, 3)->create()->each(function ($product) {
			$type = factory(ProductVariationType::class)->create([
				'product_id' => $product->id,
				'stock' => 50
			]);

			factory(ProductVariation::class)->create([
				'product_id' => $product->id,
				'product_variation_type_id' => $type->id,
			]);
			$product->carts()->save(factory(Cart::class)->create([
				'product_id' => $product->id,
				'product_variation_type_id' => $type->id,
			]));
			$product->wishlists()->save(factory(Wishlist::class)->create([
				'product_id' => $product->id,
				'product_variation_type_id' => $type->id,
				'quantity' => 30
			]));
		}));
		auth()->user()->wishlist()->saveMany(Product::get());
		$this->cartInstance = app('CartRepository');
		$this->wishlistInstance = app('WishlistRepository');
		$this->productInstance = app('ProductRepository');
		$this->typeRepo = app('ProductVariationTypeRepository');
		$this->variationRepo = app('ProductVariationRepository');
		auth()->user()->cart()->save($this->cartInstance->first());
		auth()->user()->wishlist()->save($this->wishlistInstance->first());
		$this->product = auth()->user()->cart->first()->product->first();
		$this->cart = auth()->user()->cart->first();
		$this->wishlist = auth()->user()->wishlist->first();
	}

}