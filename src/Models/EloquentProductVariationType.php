<?php

namespace SecTheater\Marketplace\Models;

use SecTheater\Marketplace\Repositories\Traits\HasSale;

class EloquentProductVariationType extends Eloquent {
	use HasSale;
    protected $table = 'product_variation_types';

	public function product() {
		return $this->belongsTo($this->productModel);
	}
	public function sales() {
		return $this->morphMany($this->saleModel, 'saleable');
	}

	public function variations() {
		return $this->hasMany($this->productVariationModel,'product_variation_type_id');
	}
	public function getDiscount()
	{
		return $this->sales->reduce(function($carry  , $sale){
			return $carry + $sale->percentage;
		});
	}
}
