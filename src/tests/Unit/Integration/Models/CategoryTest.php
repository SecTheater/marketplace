<?php

namespace SecTheater\Marketplace\Tests\Unit\Integration\Models;

use SecTheater\Marketplace\Models\EloquentCategory as Category;
use SecTheater\Marketplace\Models\EloquentProduct as Product;
use SecTheater\Marketplace\Models\EloquentSale as Sale;
use SecTheater\Marketplace\Tests\TestCase;

class CategoryTest extends TestCase {
	/** @test */
	public function it_has_many_children() {
		$category = factory(Category::class)->create();
		factory(Category::class, 2)->create([
			'parent_id' => $category->id,
		]);
		$this->assertCount(2, $category->children);
	}
	/** @test */
	public function it_has_one_parent() {
		$category = factory(Category::class)->create();
		factory(Category::class, 2)->create([
			'parent_id' => $category->id,
		]);
		$this->assertCount(1, Category::parents()->get());
	}
	/** @test */
	public function it_has_many_products() {
		$products = factory(Product::class, 3)->create();
		factory(Category::class, 2)->create();
		Category::first()->products()->saveMany($products);
		$this->assertCount(3, Category::first()->products);
	}
	/** @test */
	public function it_has_a_sale() {
		$category = factory(Category::class)->create();
		$anotherCategory = factory(Category::class)->create();
		$product = factory(Product::class)->create();
		$category->sales()->save(
			$this->createSale([
				'saleable_id' => $category->id,
				'saleable_type' => Category::class,
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
				'saleable_id' => $category->id,
				'saleable_type' => Category::class,
			])
		);
		$category->products()->attach($product);
		$this->assertCount(2, $category->sales);
		$this->assertEquals(21.0, $category->getTotalSale());
	}
}
