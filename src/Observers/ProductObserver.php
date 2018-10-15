<?php

namespace SecTheater\Marketplace\Observers;

use SecTheater\Marketplace\Models\EloquentProduct;

class ProductObserver {
	public function creating(EloquentProduct $product) {
		$product->user_id = auth()->id();
		if (auth()->user()->can('review-product',$product)) {
			$product->reviewed_by = auth()->id();
			$product->reviewed_at = date('Y-m-d H:i:s');
		}
	}
	public function updating(EloquentProduct $product) {
		$product->updated_at = date('Y-m-d H:i:s');
		if (auth()->user()->can('review-product',$product)) {
			$product->reviewed_by = auth()->id();
			$product->reviewed_at = date('Y-m-d H:i:s');
		}
	}

}