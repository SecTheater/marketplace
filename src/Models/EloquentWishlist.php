<?php

namespace SecTheater\Marketplace\Models;

use SecTheater\Marketplace\Contracts\CartInterface;

class EloquentWishlist extends Eloquent implements CartInterface {
    protected $table = 'wishlists';

	public function users() {
		return $this->belongsToMany($this->userModel, 'user_wishlist','wishlist_id','user_id');
	}
	public function product() {
		if (config('market.product.review')) {
			return $this->belongsTo($this->productModel,'product_id')->whereNotNull('reviewed_at');
			
		}
		return $this->belongsTo($this->productModel,'product_id');
	}
	public function type() {
		return $this->belongsTo($this->productVariationTypeModel, 'product_variation_type_id');
	}

}
