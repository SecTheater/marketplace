<?php

namespace SecTheater\Marketplace\Tests\Unit\Integration\Models;

use SecTheater\Marketplace\Models\EloquentProduct as Product;
use SecTheater\Marketplace\Models\EloquentProductVariation as ProductVariation;
use SecTheater\Marketplace\Models\EloquentProductVariationType as ProductVariationType;
use SecTheater\Marketplace\Tests\TestCase;

class ProductVariationTest extends TestCase {
	protected $type, $variation, $product;
	public function setUp() {
		parent::setUp();
		$this->product = factory(Product::class)->create();
		$this->type = factory(ProductVariationType::class)->create([
			'product_id' => $this->product->id,
		]);
		$this->variation = factory(ProductVariation::class)->create([
			'product_variation_type_id' => $this->type->id,
			'product_id' => $this->product->id,
		]);

	}
	/** @test */
	public function it_belongs_to_a_type() {
		$this->assertInstanceOf(ProductVariationType::class, $this->variation->type);
	}
	/** @test */
	public function it_belongs_to_a_product() {
		$this->assertInstanceOf(Product::class, $this->variation->product);
	}
	/** @test */
	public function it_has_price_attribute() {
		$this->assertEquals($this->product->price, $this->variation->price);

		$type = factory(ProductVariationType::class)->create([
			'product_id' => $this->product->id,
			'price' => 100,
		]);
		$variation = factory(ProductVariation::class)->create([
			'product_variation_type_id' => $type->id,
			'product_id' => $this->product->id,
		]);

		$this->assertEquals(100, $variation->price);
	}
	/** @test */
	public function it_has_a_stock() {
		$this->assertEquals($this->type->stock, $this->variation->stock);
	}
}