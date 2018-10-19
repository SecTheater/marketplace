<?php
namespace SecTheater\Marketplace\Tests\Unit\Integration\Repositories;

use SecTheater\Marketplace\Exceptions\InsufficientProductQuantity;
use SecTheater\Marketplace\Models\EloquentProduct as Product;
use SecTheater\Marketplace\Models\EloquentProductVariation as ProductVariation;
use SecTheater\Marketplace\Models\EloquentProductVariationType as ProductVariationType;
use SecTheater\Marketplace\Tests\TestCase;

class ProductVariationTypeRepositoryTest extends TestCase {
	protected $product, $type, $variation, $typeRepo;
	public function setUp() {
		parent::setUp();
		$this->product = factory(Product::class)->create();
		$this->type = factory(ProductVariationType::class)->create([
			'product_id' => $this->product->id,
			'stock' => 30,
		]);
		$this->variation = factory(ProductVariation::class)->create([
			'product_variation_type_id' => $this->type->id,
			'product_id' => $this->product->id,
		]);
		$this->typeRepo = app('ProductVariationTypeRepository');

	}
	/** @test */
	public function it_can_fetch_product_stock() {
		$this->assertEquals(30, $this->typeRepo->stock($this->type->id));
	}
	/** @test */
	public function it_checks_if_product_has_stock() {
		$this->assertTrue($this->typeRepo->hasStock($this->type->id));
		$this->typeRepo->first()->update(['stock' => 0]);
		$this->assertNotTrue($this->typeRepo->hasStock($this->type->id));
	}
	/** @test */
	public function it_checks_if_product_is_in_stock() {
		$this->typeRepo->first()->update(['stock' => 0]);
		$this->assertNotTrue($this->typeRepo->inStock($this->type->id));
		$this->typeRepo->first()->update(['stock' => 10]);
		$this->assertTrue($this->typeRepo->inStock($this->type->id));

	}
	/** @test */
	public function it_product_is_low_stock() {
		$this->typeRepo->first()->update(['stock' => 0]);
		$this->assertNotTrue($this->typeRepo->lowStock($this->type->id));
		$this->typeRepo->first()->update(['stock' => 3]);
		$this->assertTrue($this->typeRepo->lowStock($this->type->id));
	}
	/** @test */
	public function it_increments_stock() {
		$this->assertEquals(31, $this->typeRepo->incrementStock($this->type->id)->stock);
	}
	/** @test */
	public function it_decrements_stock() {
		$this->assertEquals(29, $this->typeRepo->decrementStock($this->type->id)->stock);
		$this->assertEquals(19, $this->typeRepo->decrementStock($this->type->id, 10)->stock);
		$this->assertEquals(0, $this->typeRepo->decrementStock($this->type->id, 19)->stock);
		$this->typeRepo->incrementStock($this->type->id);
		try {
			$this->typeRepo->decrementStock($this->type->id, 10);
		} catch (InsufficientProductQuantity $e) {
			$this->assertEquals(1,$this->type->fresh()->stock);
		}
	}

}