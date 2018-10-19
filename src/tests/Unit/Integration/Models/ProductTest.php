<?php

namespace SecTheater\Marketplace\Tests\Unit\Integration\Models;

use Carbon\Carbon;
use SecTheater\Marketplace\Models\EloquentCategory as Category;
use SecTheater\Marketplace\Models\EloquentCoupon as Coupon;
use SecTheater\Marketplace\Models\EloquentProduct as Product;
use SecTheater\Marketplace\Models\EloquentProductVariation as ProductVariation;
use SecTheater\Marketplace\Models\EloquentProductVariationType as ProductVariationType;
use SecTheater\Marketplace\Models\EloquentSale as Sale;
use SecTheater\Marketplace\Models\EloquentUser as User;
use SecTheater\Marketplace\Tests\TestCase;

class ProductTest extends TestCase {
	/** @test */
	public function it_has_many_categories() {
		$product = factory(Product::class)->create();
		$product->categories()->save(
			factory(Category::class)->create()
		);
		$this->assertInstanceOf(Category::class, $product->categories->first());
	}
	/** @test */
	public function it_has_owner() {
		$product = factory(Product::class)->create([
			'user_id' => auth()->id(),
		]);
		$this->assertInstanceOf(User::class, $product->owner);
		$this->assertEquals(auth()->id(), $product->owner->id);
	}
	/** @test */
	public function it_has_many_variations() {
		$product = factory(Product::class)->create();
		$type = factory(ProductVariationType::class)->create([
			'product_id' => $product->id,
		]);
		$product->variations()->save(
			factory(ProductVariation::class)->create([
				'product_id' => $product->id,
				'product_variation_type_id' => $type->id,
			])
		);
		$this->assertInstanceOf(ProductVariation::class, $product->variations->first());
	}
	/** @test */
	public function it_has_a_sale() {
		$product = factory(Product::class)->create();
		$anotherProduct = factory(Product::class)->create();
		$product->sales()->save(
			$this->createSale([
				'saleable_id' => $product->id,
				'saleable_type' => Product::class,
			])
		);
		$product->sales()->save(
			$this->createSale([
				'saleable_id' => $product->id,
				'saleable_type' => Product::class,
			])
		);
		$product->sales()->save(
			$this->createSale([
				'saleable_id' => $product->id,
				'saleable_type' => Product::class,
				'expires_at' => Carbon::now()->subDays(5)->format('Y-m-d H:i:s')
			])
		);

		$this->assertCount(3, $product->sales);
		$this->assertEquals(21.0, $product->fresh()->getDiscount());
	}
	/** @test */
	public function it_has_sale_on_category() {
		$category = factory(Category::class)->create();
		$anotherCategory = factory(Category::class)->create();
		$product = factory(Product::class)->create();
		$type = factory(ProductVariationType::class)->create([
			'product_id' => $product->id,
		]);
		$anotherType = factory(ProductVariationType::class)->create([
			'product_id' => $product->id,
		]);
		$type->sales()->save(
			$this->createSale([
				'saleable_id' => $type->id,
				'saleable_type' => ProductVariationType::class,
			])
		);
		$anotherType->sales()->save(
			$this->createSale([
				'saleable_id' => $anotherType->id,
				'saleable_type' => ProductVariationType::class,
			])

		);
		$product->sales()->save(
			$this->createSale([
				'saleable_id' => $product->id,
				'saleable_type' => Product::class,
			])
		);
		$category->sales()->save(
			$this->createSale([
				'saleable_id' => $category->id,
				'saleable_type' => Category::class,
			])

		);
		$anotherCategory->sales()->save(
			$this->createSale([
				'saleable_id' => $anotherCategory->id,
				'saleable_type' => Category::class,
			])

		);
		$category->products()->attach($product);
		$anotherCategory->products()->attach($product);
		$this->assertTrue($product->fresh()->has_sale);
		$this->assertEquals(52.5, $product->fresh()->sale);
	}
}
