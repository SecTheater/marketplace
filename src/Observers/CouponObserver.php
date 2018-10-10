<?php

namespace SecTheater\Marketplace\Observers;

use SecTheater\Marketplace\Models\EloquentCoupon;

class CouponObserver {
	public function creating(EloquentCoupon $coupon) {
		if (!$coupon->code) {
			$coupon->code = str_random(32);
		}
	}
}