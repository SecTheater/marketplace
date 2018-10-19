<?php

namespace SecTheater\Marketplace\Tests\Unit\Integration\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use SecTheater\Marketplace\Exceptions\CouponCanNotBePurchasedException;
use SecTheater\Marketplace\Exceptions\CouponExpiredException;
use SecTheater\Marketplace\Models\EloquentUser as User;
use SecTheater\Marketplace\Tests\TestCase;
use SecTheater\Marketplace\Models\EloquentCoupon as Coupon;
class CouponRepositoryTest extends TestCase {
	public function setUp() {
		parent::setUp();
		$this->couponRepo = app('CouponRepository');
	}
	/** @test */
	public function it_generates_a_code() {
		$this->couponRepo->generate([
			'user_id' => auth()->id(),
			'code' => str_random(32)
		]);
		$this->assertEquals(32, strlen($this->couponRepo->first()->code));
	}
	/** @test */
	public function it_has_a_valid_coupon() {
		$coupon = $this->couponRepo->generate([
			'user_id' => auth()->id(),
			'amount' => 10,
			'active' => true,
			'code' => str_random(32)

		]);
		$this->assertTrue($this->couponRepo->validate($coupon));
		$coupon = $this->couponRepo->generate([
			'user_id' => auth()->id(),
			'amount' => 0,
			'active' => true,
			'code' => str_random(32)

		]);
		$this->assertFalse($this->couponRepo->validate($coupon));
		$anotherCoupon = $this->couponRepo->generate([
			'user_id' => auth()->id(),
			'expires_at' => Carbon::now()->subDays(7)->format('Y-m-d H:i:s'),
			'code' => str_random(32)

		]);
		$coupon->update([
			'active' => false,
		]);
		$this->assertFalse($this->couponRepo->validate($coupon));
		try {
			$this->couponRepo->validate($anotherCoupon);
			
		} catch (CouponExpiredException $e) {
			$this->assertEquals("Coupon is already expired.",$e->getMessage());			
		}
	}
	/** @test */
	public function it_has_percentage() {
		$coupon = $this->couponRepo->generate([
			'user_id' => auth()->id(),
			'percentage' => 23.2,
			'code' => str_random(32)

		]);
		$this->assertEquals($coupon->percentage, 23.2);
	}
	/** @test */
	public function it_deactivates_a_coupon() {
		$coupon = $this->couponRepo->generate([
			'user_id' => auth()->id(),
			'code' => str_random(32)

		]);
		$this->assertTrue($this->couponRepo->deactivate($coupon->id));
		try {
			$this->assertTrue($this->couponRepo->deactivate(10));
		} catch (ModelNotFoundException $e) {
			$this->assertEquals(10,$e->getIds()[0]);			
		}
	}
	/** @test */
	public function it_activates_a_coupon() {
		$coupon = $this->couponRepo->generate([
			'user_id' => auth()->id(),
			'active' => false,
			'code' => str_random(32)

		]);
		$this->assertFalse($coupon->active);
		$this->assertTrue($this->couponRepo->activate($coupon->id));
	}
	/** @test */
	public function it_regenerates_a_coupon_code() {
		$coupon = $this->couponRepo->generate([
			'code' => str_random(32),

			'user_id' => auth()->id(),
		]);
		$code = $coupon->code;
		$this->assertNotEquals($this->couponRepo->regenerate($coupon->id)->code, $code);
		$this->assertEquals($this->couponRepo->regenerate($coupon->id, 'some-random-string')->code, 'some-random-string');
		try {
			$this->assertTrue($this->couponRepo->regenerate(15));
		} catch (ModelNotFoundException $e) {
			$this->assertEquals(15,$e->getIds()[0]);			
		}
	}
	/** @test */
	public function it_validates_and_regenerates_coupon() {
		$coupon = $this->couponRepo->generate([
			'user_id' => auth()->id(),
			'expires_at' => Carbon::now()->subDays(7)->format('Y-m-d H:i:s'),
			'code' => str_random(32)

		]);
		$this->assertInstanceOf(Coupon::class,$this->couponRepo->regenerateAndValidate($coupon->id));
		$coupon->update([
			'active' => false,
			'amount' => 0
		]);
		$this->assertInstanceOf(Coupon::class,$this->couponRepo->regenerateAndValidate($coupon->id));
		$this->assertEquals(['active' => true , 'amount' => 1 ],['active' => $coupon->fresh()->active , 'amount' => $coupon->fresh()->amount]);
		$coupon->fresh()->update([
			'active' => false,
			'amount' => 0,
			'expires_at' => Carbon::now()->subDays(7)->format('Y-m-d H:i:s'),
		]);
		$this->assertInstanceOf(Coupon::class,$this->couponRepo->regenerateAndValidate($coupon->id));
		$this->assertEquals(['active' => false , 'amount' => 0 ],['active' => $coupon->fresh()->active , 'amount' => $coupon->fresh()->amount]);

	}
	/** @test */
	public function it_can_purchase_to_a_coupon() {
		$user = factory(User::class)->create();
		$coupon = $this->couponRepo->generate([
			'user_id' => $user->id,
			'amount' => 10,
			'active' => true,
			'code' => str_random(32)

		]);
		$this->assertEquals(9, $this->couponRepo->purchase($coupon)->amount);
		$coupon = $this->couponRepo->generate([
			'user_id' => auth()->id(),
			'amount' => 10,
			'active' => true,
			'code' => str_random(32)
		]);
		try {
			$this->couponRepo->purchase($coupon);
		} catch (CouponCanNotBePurchasedException $e) {
			$this->assertEquals(10 , $coupon->fresh()->amount);			
		}

	}
	/** @test */
	public function it_cannot_purchase_a_purchsed_coupon() {
		$user = factory(User::class)->create();
		$coupon = $this->couponRepo->generate([
			'user_id' => $user->id,
			'amount' => 10,
			'active' => true,
			'code' => str_random(32)
		]);
		$this->couponRepo->purchase($coupon);
		try {
			$this->couponRepo->purchase($coupon);
		} catch (CouponCanNotBePurchasedException $e) {
			$this->assertEquals(9 , $coupon->fresh()->amount);			
		}
	}
	/** @test */
	public function it_can_release_only_purchased_coupons() {
		$user = factory(User::class)->create();
		$coupon = $this->couponRepo->generate([
			'user_id' => $user->id,
			'amount' => 10,
			'active' => true,
			'code' => str_random(32)

		]);
		$this->couponRepo->purchase($coupon);
		$this->assertEquals(0, $this->couponRepo->release($coupon)->users()->count());
	}
	/** @test */
	public function it_retrieves_applied_coupons_only() {
		$user = factory(User::class)->create();
		$coupon = app('CouponRepository')->generate([
			'user_id' => $user->id,
			'expires_at' => Carbon::now()->addDays(7)->format('Y-m-d H:i:s'),
			'active' => true,
			'amount' => 12,
			'code' => str_random(32)

		]);
		$anotherCoupon = app('CouponRepository')->generate([
			'user_id' => $user->id,
			'expires_at' => Carbon::now()->addDays(7)->format('Y-m-d H:i:s'),
			'active' => true,
			'amount' => 10,
			'code' => str_random(32)

		]);
		$this->couponRepo->purchase($coupon);
		$this->assertCount(1, $this->couponRepo->appliedCoupons($this->couponRepo->all()));
	}
}
