<?php

namespace SecTheater\Marketplace\Tests\Unit\Integration\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use SecTheater\Marketplace\Models\EloquentCart as Cart;
use SecTheater\Marketplace\Models\EloquentCategory as Category;
use SecTheater\Marketplace\Models\EloquentProduct as Product;
use SecTheater\Marketplace\Models\EloquentProductVariation as ProductVariation;
use SecTheater\Marketplace\Tests\TestCase;
use SecTheater\Marketplace\Tests\Unit\CartTrait;

class ProductRepositoryTest extends TestCase {
	use CartTrait;
	/** @test */
	public function it_can_create_a_product_covering_all_aspects() {
		$this->assertInstanceOf(ProductVariation::class, $this->productInstance->generate([
			'user_id' => auth()->id(),
			'name' => 'laptop',
			'description' => 'Fancy laptop',
			'price' => 15000,
			'category' => 'electronics',
			'type' => ['name' => 'MacBook Pro', 'stock' => 20],
			'details' => ['color' => 'Red', 'size' => 'XXL', 'brand' => 'lacoste'],
		])->variations->first());
		$this->assertInstanceOf(Product::class, $this->productInstance->generate([
			'user_id' => auth()->id(),
			'name' => 'Fancy T-Shirt',
			'description' => 'Idk a shit about it',
			'price' => 80,
			'category' => 'clothes',
			'type' => ['name' => 'MacBook Pro', 'stock' => 20],
			'details' => ['color' => 'Red', 'size' => 'XXL', 'brand' => 'lacoste'],
		]));
		$this->assertFalse($this->productInstance->generate([
			'user_id' => auth()->id(),
			'name' => 'Fancy T-Shirt',
			'description' => 'Idk a shit about it',
			'price' => 80,
			'category' => 'clothes',
			'type' => ['name' => 'MacBook Pro', 'stock' => 20],
			'details' => ['color' => 'Red', 'size' => 'XXL', 'brand' => 'lacoste'],
		])->wasRecentlyCreated);

	}
	/** @test */
	public function it_can_fetch_product_by_type() {
		$category = factory(Category::class)->create();
		$this->productInstance->first()->categories()->attach($category);

		$this->assertInstanceOf(Collection::class, $this->productInstance->fetchByCategories($category->type)->get());
	}
	/** @test */
	public function it_gets_the_current_value_of_between_query_property() {
		$this->assertNull($this->productInstance->getBetweenQuery());
	}
	/** @test */
	public function it_sets_the_value_of_between_query_property() {
		$this->productInstance->setBetweenQuery('something-random');
		$this->assertEquals('something-random', $this->productInstance->getBetweenQuery());
	}
	/** @test */
	public function it_has_a_between_query_property_that_is_not_set_to_null() {
		$this->productInstance->setBetweenQuery('something-random');
		$this->assertTrue($this->productInstance->hasBetweenQuery());
	}
	/** @test */
	public function it_retrieves_by_prices_between_min_and_max() {
		$this->assertInstanceOf(Collection::class, $this->productInstance->betweenPrice(30, 100)->get());
	}
	/** @test */
	public function it_fetches_products_by_date_time() {
		$from = $this->productInstance->first()->created_at->toDateTimeString();
		$to = $this->productInstance->latest()->first()->created_at->toDateTimeString();
		$this->assertInstanceOf(Collection::class, $this->productInstance->betweenCreatedAt($from, $to)->get());
	}
	/** @test */
	public function it_tests_the_between_method_with_and_clause() {
		$from = $this->productInstance->first()->created_at->format('Y-m-d H:i:s');
		$to = Carbon::now()->addDays(1)->format('Y-m-d H:i:s');
		$this->assertInstanceOf(Collection::class, $this->productInstance->betweenCreatedAtAndPrice([$from, $to], [30, 50])->get());
	}
	/** @test */
	public function it_tests_the_between_method_with_or_clause() {
		$from = $this->productInstance->first()->created_at->format('Y-m-d H:i:s');
		$to = Carbon::now()->addDays(1)->format('Y-m-d H:i:s');
		$this->assertInstanceOf(Collection::class, $this->productInstance->betweenCreatedAtOrPrice([$from, $to], [300000, 50000])->get());
	}
	/** @test */
	public function it_fetches_products_by_location() {
		$location = auth()->user()->location;
		$this->assertInstanceOf(Collection::class, $this->productInstance->fetchByLocation($location)->get());
	}
	/** @test */
	public function it_fetches_products_by_category() {
		$this->product->categories()->attach($this->category);
		$this->assertCount(1, $this->productInstance->fetchByCategories($this->category->type)->get());
	}
	/** @test */
	public function it_reviews_the_product() {
		$this->assertTrue($this->productInstance->approve($this->product->id));
	}
	/** @test */
	public function it_fetches_trendy_products() {
		$this->product->carts()->save(factory(Cart::class)->create([
			'product_id' => $this->product->id,
			'product_variation_type_id' => $this->typeRepo->first()->id,
		]));
		$this->assertEquals(2, $this->productInstance->fetchTrendyByLocation('Egypt')->first()->carts_count);
	}
	/** @test */
	public function it_filters_against_various_variations() {

		$this->product->categories()->attach($this->category);
		$type = $this->category->type;
		$this->assertCount(0, $this->productInstance->fetchByVariations(['locations' => 'Egypt', 'variations' => ['size' => 'XL', 'color' => 'red'], 'categories' => $type]));
	}
	/** @test */
	public function it_filters_by_custom_variations()
	{
		$this->productInstance->addCriterion('fetchNotReviewedOrExpired' , function($date = null){
			return $this->productInstance->whereReviewedAt($date);
		});
		$this->productInstance->first()->fill(['reviewed_at' => null])->save();
		$this->assertEquals(1,$this->productInstance->fetchByLocation('Egypt')->whereReviewedAt(null)->count());
		$this->assertEquals(1,$this->productInstance->fetchByVariations(['fetchNotReviewedOrExpired' => []])->count());
		$this->assertEquals(1,$this->productInstance->fetchByVariations(['locations' => 'Egypt','fetchNotReviewedOrExpired' => ['date' => null]])->count());
	}
}