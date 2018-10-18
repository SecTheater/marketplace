<?php

namespace SecTheater\Marketplace\Tests\Unit\Integration\Observers;

use SecTheater\Marketplace\Models\EloquentCoupon;
use SecTheater\Marketplace\Models\EloquentUser;
use SecTheater\Marketplace\Tests\TestCase;

class CouponObserverTest extends TestCase {
    /** @test */
    public function it_fires_creating_event_on_creating_a_product()
    {
        \Event::fake();
        $user = factory(EloquentUser::class)->create();
        $this->actingAs($user);
        $coupon = factory(EloquentCoupon::class)->create([
            'user_id' => auth()->id()
        ]);
        \Event::assertDispatched('eloquent.creating: SecTheater\Marketplace\Models\EloquentCoupon');
    }
    /** @test **/
    public function it_fires_updating_event_on_updating_a_product()
    {
        \Event::fake();
        $user = factory(EloquentUser::class)->create();
        $this->actingAs($user);
        $coupon = factory(EloquentCoupon::class)->create([
            'user_id' => auth()->id()
        ]);
        $coupon->update([
            'expires_at' => null
        ]);
        \Event::assertDispatched('eloquent.updating: SecTheater\Marketplace\Models\EloquentCoupon');
    }
}
