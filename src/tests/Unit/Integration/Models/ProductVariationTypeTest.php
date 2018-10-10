<?php

namespace SecTheater\Marketplace\Tests\Unit\Integration\Models;

use SecTheater\Marketplace\Models\EloquentProduct as Product;
use SecTheater\Marketplace\Models\EloquentProductVariation as ProductVariation;
use SecTheater\Marketplace\Models\EloquentProductVariationType as ProductVariationType;
use SecTheater\Marketplace\Models\EloquentSale as Sale;
use SecTheater\Marketplace\Tests\TestCase;

class ProductVariationTypeTest extends TestCase {
	/** @test */
	public function it_belongs_to_product() {
		$product = factory(Product::class)->create();
		$type = factory(ProductVariationType::class)->create([
			'product_id' => $product->id,
		]);
		$this->assertInstanceOf(Product::class, $type->product);
	}
	/** @test */
	public function it_has_many_variations() {
		$product = factory(Product::class)->create();
		$type = factory(ProductVariationType::class)->create([
			'product_id' => $product->id,
		]);
		factory(ProductVariation::class, 2)->create([
			'product_variation_type_id' => $type->id,
		]);
		$this->assertCount(2, $type->variations);
	}
	/** @test */
	public function it_has_a_sale() {
		$product = factory(Product::class)->create();
		$type = factory(ProductVariationType::class)->create([
			'product_id' => $product->id,
		]);

		$type->sales()->save(
			$this->createSale([
				'saleable_id' => $product->id,
				'saleable_type' => 'product_type',
			])
		);
		$this->assertCount(1, $type->sales);
	}
}