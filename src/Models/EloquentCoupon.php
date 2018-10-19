<?php

namespace SecTheater\Marketplace\Models;

class EloquentCoupon extends Eloquent {
	protected $guarded = ['id'];
	protected $dates = ['expires_at'];
    protected $table = 'coupons';
    protected $casts = [
        'active' => 'boolean',
        'amount' => 'integer',
    ];
	public function owner() {
		return $this->belongsTo($this->userModel, 'user_id');
	}
	public function users() {
		return $this->belongsToMany($this->userModel, 'user_coupon', 'coupon_id', 'user_id')->withPivot('purchased');
	}
}
