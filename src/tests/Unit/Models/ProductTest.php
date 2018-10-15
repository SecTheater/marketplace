<?php

namespace SecTheater\Marketplace\Tests\Unit\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use SecTheater\Marketplace\Contracts\ProductInterface;
use SecTheater\Marketplace\Models\EloquentProduct;
use SecTheater\Marketplace\Models\EloquentUser;
use SecTheater\Marketplace\Tests\TestCase;

class ProductTest extends TestCase {
  public function setUp()
  {
    parent::setUp();
    $this->product = new EloquentProduct;
  }
  /** @test */
  public function it_implements_product_interface()
  {
    $this->assertInstanceOf(ProductInterface::class, $this->product);
  }
  /** @test */
  public function it_has_products_table()
  {
    $this->assertEquals('products' , $this->product->getTable());
  }
  /** @test */
  public function it_has_initial_sale_with_zero()
  {
    $this->assertEquals(0 , $this->product->sale);
  }
  /** @test */
  public function it_has_sale_set_to_false_by_default()
  {
    $this->assertFalse($this->product->has_sale);
  }
  /** @test */
  public function it_has_zero_discounts_initially()
  {
    $this->assertEquals(0 , $this->product->getDiscount());
  }
  /** @test */
  public function it_should_review_products()
  {
    $this->assertTrue($this->product->shouldBeReviewed());
    config(['market.product.review' => false]);
    $this->assertFalse($this->product->shouldBeReviewed());
    config(['market.product.review' => true]);
  }
  /** @test */
  public function it_has_owner_relationship()
  {
    $this->assertInstanceOf(BelongsTo::class , $this->product->owner());
    $this->assertEquals('user_id' , $this->product->owner()->getForeignKey());
  }
  /** @test */
  public function it_has_many_wishlists_relationship()
  {
    $this->assertInstanceOf(HasMany::class, $this->product->wishlists());
    $this->assertEquals('product_id' , $this->product->wishlists()->getForeignKeyName());
  }
  /** @test */
  public function it_has_many_carts_relationship()
  {
    $this->assertInstanceOf(HasMany::class, $this->product->carts());
    $this->assertEquals('product_id' , $this->product->carts()->getForeignKeyName());
  }
  
  /** @test */
  public function it_has_many_types_relationship()
  {
    $this->assertInstanceOf(HasMany::class, $this->product->types());
    $this->assertEquals('product_id' , $this->product->types()->getForeignKeyName());
  }
  /** @test */
  public function it_has_categories_with_many_to_many_relationship()
  {
    $this->assertInstanceOf(BelongsToMany::class,$this->product->categories());
    $this->assertEquals('category_product',$this->product->categories()->getTable());
    $this->assertEquals('product_id',$this->product->categories()->getForeignPivotKeyName());
    $this->assertEquals('category_id',$this->product->categories()->getRelatedPivotKeyName());

  }
  /** @test */
  public function it_has_sales_with_morph_many_relationship()
  {
    $this->assertInstanceOf(MorphMany::class , $this->product->sales());
    $this->assertEquals('saleable_type',$this->product->sales()->getMorphType());
    $this->assertEquals(EloquentProduct::class,$this->product->sales()->getMorphClass());
  }

}