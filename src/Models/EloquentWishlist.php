<?php

namespace SecTheater\Marketplace\Models;

use SecTheater\Marketplace\Contracts\CartInterface;

class EloquentWishlist extends Eloquent implements CartInterface {
	protected $guarded = [];
    protected $table = 'wishlists';

	public function user() {
		return $this->belongsToMany($this->userModel, 'user_wishlist','user_id');
	}
	public function product() {
		return $this->belongsTo($this->productModel)->where('reviewed', true);
	}
	public function type() {
		return $this->belongsTo($this->productVariationTypeModel, 'product_variation_type_id');
	}

}
