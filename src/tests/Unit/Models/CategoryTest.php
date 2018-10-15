<?php

namespace SecTheater\Marketplace\Tests\Unit\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use SecTheater\Marketplace\Models\EloquentCategory;
use SecTheater\Marketplace\Models\EloquentSale;
use SecTheater\Marketplace\Tests\TestCase;

class CategoryTest extends TestCase {
    public function setUp()
    {
        parent::setUp();
        $this->category = new EloquentCategory;
    }
    /** @test */
    public function it_has_categories_table()
    {
       $this->assertEquals('categories',$this->category->getTable());
    }
    /** @test */
    public function it_has_all_fillable_except_for_id_column()
    {
       $this->assertEquals(['id'],$this->category->getGuarded());
    }
    /** @test */
    public function it_has_scope_parents()
    {
        $this->assertInstanceOf(Builder::class,$this->category->parents());
    }
    /** @test */
    public function it_has_sales_with_morph_many_relationship()
    {
        $this->assertInstanceOf(MorphMany::class , $this->category->sales());
        $this->assertEquals('saleable_type',$this->category->sales()->getMorphType());
        $this->assertEquals(EloquentCategory::class,$this->category->sales()->getMorphClass());
    }
    /** @test */
    public function it_has_many_children_relationship()
    {
       $this->assertInstanceOf(HasMany::class, $this->category->children());
       $this->assertEquals('parent_id' , $this->category->children()->getForeignKeyName());
    }
}