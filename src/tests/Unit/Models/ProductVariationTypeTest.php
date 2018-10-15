<?php

namespace SecTheater\Marketplace\Tests\Unit\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use SecTheater\Marketplace\Models\EloquentProduct;
use SecTheater\Marketplace\Models\EloquentProductVariationType;
use SecTheater\Marketplace\Tests\TestCase;

class ProductVariationTypeTest extends TestCase {
    public function setUp()
    {
        parent::setUp();
        $this->type = new EloquentProductVariationType;
    }
    /** @test */
    public function it_has_product_variation_types_table()
    {
       $this->assertEquals('product_variation_types' , $this->type->getTable());
    }
    /** @test */
    public function it_has_all_fillable_except_for_id_column()
    {
        $this->assertEquals(['id'] , $this->type->getGuarded());
    }
    /** @test */
    public function it_has_product_relationship()
    {
      $this->assertInstanceOf(BelongsTo::class , $this->type->product());
      $this->assertEquals('product_id' , $this->type->product()->getForeignKey());
    }
    /** @test */
    public function it_has_sales_with_morph_many_relationship()
    {
        $this->assertInstanceOf(MorphMany::class , $this->type->sales());
        $this->assertEquals('saleable_type',$this->type->sales()->getMorphType());
        $this->assertEquals(EloquentProductVariationType::class,$this->type->sales()->getMorphClass());
    }
    /** @test */
    public function it_has_variations_relationship()
    {
        $this->assertInstanceOf(HasMany::class, $this->type->variations());
       $this->assertEquals('product_variation_type_id' , $this->type->variations()->getForeignKeyName());

    }
    /** @test */
    public function it_gets_the_discount()
    {
        $this->assertEquals(0 , $this->type->getDiscount());
    }
}

