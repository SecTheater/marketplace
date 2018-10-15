<?php

namespace SecTheater\Marketplace\Tests\Unit\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use SecTheater\Marketplace\Models\EloquentUser;
use SecTheater\Marketplace\Tests\TestCase;

class UserTest extends TestCase {
  public function setUp()
  {
    parent::setUp();
    $this->user = new EloquentUser;
  }
  /** @test */
  public function it_has_users_table()
  {
    $this->assertEquals('users', $this->user->getTable());
  }
  /** @test */
  public function it_has_every_column_fillable_except_for_id()
  {
    $this->assertEquals(['id'] , $this->user->getGuarded());
  }
  /** @test */
  public function it_casts_permissions_to_array()
  {
    $this->user->permissions = ['something' => 'another'];
    $this->assertTrue(is_array($this->user->permissions));
  }
  /** @test */
  public function it_has_cart_with_many_to_many_relationship()
  {
    $this->assertInstanceOf(BelongsToMany::class,$this->user->cart());
    $this->assertEquals('cart_user',$this->user->cart()->getTable());

    $this->assertEquals('user_id',$this->user->cart()->getForeignPivotKeyName());

    $this->assertEquals('cart_id',$this->user->cart()->getRelatedPivotKeyName());

  }
  /** @test */
  public function it_has_wishlist_with_many_to_many_relationship()
  {
    $this->assertInstanceOf(BelongsToMany::class,$this->user->wishlist());
    $this->assertEquals('user_wishlist',$this->user->wishlist()->getTable());

    $this->assertEquals('user_id',$this->user->wishlist()->getForeignPivotKeyName());

    $this->assertEquals('wishlist_id',$this->user->wishlist()->getRelatedPivotKeyName());

  }
  /** @test */
  public function it_has_coupons_with_many_to_many_relationship()
  {
    $this->assertInstanceOf(BelongsToMany::class,$this->user->coupons());
    $this->assertEquals('user_coupon',$this->user->coupons()->getTable());
    $this->assertEquals('user_id',$this->user->coupons()->getForeignPivotKeyName());
    $this->assertEquals('coupon_id',$this->user->coupons()->getRelatedPivotKeyName());

  }
  /** @test */
  public function it_has_roles_with_many_to_many_relationship()
  {
    $this->assertInstanceOf(BelongsToMany::class,$this->user->roles());
    $this->assertEquals('role_user',$this->user->roles()->getTable());
    $this->assertEquals('user_id',$this->user->roles()->getForeignPivotKeyName());
    $this->assertEquals('role_id',$this->user->roles()->getRelatedPivotKeyName());
  }
  /** @test */
  public function it_has_products_with_has_many_relationship()
  {
    $this->assertInstanceOf(HasMany::class,($this->user->products()));     
    $this->assertEquals('user_id',$this->user->products()->getForeignKeyName());
    $this->assertEquals('users.id',$this->user->products()->getQualifiedParentKeyName());
  }


}