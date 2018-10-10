<?php

namespace SecTheater\Marketplace\Models;

use SecTheater\Marketplace\Contracts\CartInterface;

class EloquentCart extends Eloquent implements CartInterface {

	protected $table = 'carts';
	public function user() {
		return $this->belongsToMany($this->userModel, 'cart_user');
	}
	public function product() {
		return $this->belongsTo($this->productModel,'product_id')->where('reviewed', true);
	}
	public function type() {
		return $this->belongsTo($this->productVariationTypeModel, 'product_variation_type_id');
	}
}
