<?php

namespace SecTheater\Marketplace\Tests\Unit\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SecTheater\Marketplace\Models\EloquentProduct;
use SecTheater\Marketplace\Models\EloquentProductVariation;
use SecTheater\Marketplace\Tests\TestCase;

class ProductVariationTest extends TestCase {
    public function setUp()
    {
        parent::setUp();
        $this->variation = new EloquentProductVariation;
    }
    /** @test */
    public function it_has_product_variation_type_relationship()
    {
      $this->assertInstanceOf(BelongsTo::class , $this->variation->type());
      $this->assertEquals('product_variation_type_id' , $this->variation->type()->getForeignKey());
    }
    /** @test */
    public function it_has_product_relationship()
    {
      $this->assertInstanceOf(BelongsTo::class , $this->variation->product());
      $this->assertEquals('product_id' , $this->variation->product()->getForeignKey());
    }

}