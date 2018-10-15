<?php

namespace SecTheater\Marketplace\Tests\Unit\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use SecTheater\Marketplace\Models\EloquentSale;
use SecTheater\Marketplace\Tests\TestCase;

class SaleTest extends TestCase {
    public function setUp()
    {
        parent::setUp();
        $this->sale = new EloquentSale;
    }
    /** @test */
    public function it_has_users_table()
    {
        $this->assertEquals('sales', $this->sale->getTable());
    }
    /** @test */
    public function it_has_every_column_fillable_except_for_id()
    {
        $this->assertEquals(['id'] , $this->sale->getGuarded());
    }
    /** @test */
    public function it_casts_permissions_to_array()
    {
        $this->sale->active = 1;
        $this->assertTrue($this->sale->active);
    }
    /** @test */
    public function it_has_one_to_many_morph_sales_relationship()
    {
        $this->assertEquals('saleable_id', $this->sale->saleable()->getForeignKey());
        $this->assertEquals('saleable_type', $this->sale->saleable()->getMorphType());
        $this->assertInstanceOf(EloquentSale::class,$this->sale->saleable()->getModel());
    }
}
