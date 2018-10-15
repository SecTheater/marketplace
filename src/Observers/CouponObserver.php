<?php

namespace SecTheater\Marketplace\Observers;

use SecTheater\Marketplace\Models\EloquentCoupon;

class CouponObserver {
	public function creating(EloquentCoupon $coupon) {
		if (!$coupon->code) {
			$coupon->code = str_random(32);
		}
        if (auth()->check() && ! $coupon->user_id) {
            $coupon->user_id = auth()->id();
        }
	}
    public function updating(EloquentCoupon $coupon)
    {
        if (!$coupon->code) {
            $coupon->code = str_random(32);
        }
        if (auth()->check() && ! $coupon->user_id) {
            $coupon->user_id = auth()->id();
        }

    }
}