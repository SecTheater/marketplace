<?php

namespace SecTheater\Marketplace\Tests\Unit\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use SecTheater\Marketplace\Models\EloquentCoupon;
use SecTheater\Marketplace\Tests\TestCase;

class CouponTest extends TestCase {
    public function setUp()
    {
        parent::setUp();
        $this->coupon = new EloquentCoupon;
    }
    /** @test */
    public function it_has_coupons_table()
    {
       $this->assertEquals('coupons' , $this->coupon->getTable());
    }
    /** @test */
    public function it_instantiates_expires_at_column_from_carbon()
    {
        $this->coupon->expires_at = date('Y-m-d H:i:s');
       $this->assertInstanceOf(Carbon::class, $this->coupon->expires_at);
    }
    /** @test */
    public function it_has_every_column_fillable_except_for_id()
    {
       $this->assertEquals(['id'] , $this->coupon->getGuarded());
    }
    /** @test */
    public function it_has_owner_relationship()
    {
      $this->assertInstanceOf(BelongsTo::class , $this->coupon->owner());
       $this->assertEquals('user_id' , $this->coupon->owner()->getForeignKey());

    }
    /** @test */
    public function it_has_users_with_many_to_many_relationship()
    {
       $this->assertInstanceOf(BelongsToMany::class,$this->coupon->users());
       $this->assertEquals('user_coupon',$this->coupon->users()->getTable());
       $this->assertEquals('coupon_id',$this->coupon->users()->getForeignPivotKeyName());
       $this->assertEquals('user_id',$this->coupon->users()->getRelatedPivotKeyName());

    }

}