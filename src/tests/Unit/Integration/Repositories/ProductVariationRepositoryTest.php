<?php
namespace SecTheater\Marketplace\Tests\Unit\Integration\Repositories;

use SecTheater\Marketplace\Models\EloquentProduct as Product;
use SecTheater\Marketplace\Models\EloquentProductVariation as ProductVariation ;
use SecTheater\Marketplace\Models\EloquentProductVariationType as ProductVariationType;
use SecTheater\Marketplace\Tests\TestCase;

class ProductVariationRepositoryTest extends TestCase {
	protected $product, $type, $variation, $typeRepo;
	public function setUp() {
		parent::setUp();
		$this->product = factory(Product::class)->create();
		$this->type = factory(ProductVariationType::class)->create([
			'product_id' => $this->product->id,
			'stock' => 30,
		]);
		$anotherType = factory(ProductVariationType::class)->create([
			'product_id' => $this->product->id,
			'stock' => 50,
		]);
		$this->variation = factory(ProductVariation::class)->create([
			'product_variation_type_id' => $this->type->id,
			'product_id' => $this->product->id,
		]);
		factory(ProductVariation::class)->create([
			'product_variation_type_id' => $anotherType->id,
			'product_id' => $this->product->id,
			'details' => ['color' => 'red', 'size' => 'XL'],
		]);
		$this->variationRepo = app('ProductVariationRepository');

	}
	/** @test */
	public function it_retrieves_filtered_variations() {
		// partially matching.
		$this->assertCount(2, $this->variationRepo->contains($this->product->types, ['color' => 'green', 'size' => 'XL']));
		// does not match at all.
		$this->assertCount(0, $this->variationRepo->contains($this->product->types, ['color' => 'green', 'size' => 'L']));
	}
}