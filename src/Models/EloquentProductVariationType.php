<?php

namespace SecTheater\Marketplace\Models;

use SecTheater\Marketplace\Repositories\Traits\HasSale;

class EloquentProductVariationType extends Eloquent {
	use HasSale;
    protected $table = 'product_variation_types';

	public function product() {
		return $this->belongsTo($this->productModel);
	}
	public function variations() {
		return $this->hasMany($this->productVariationModel,'product_variation_type_id');
	}
	public function getDiscount()
	{
		return $this->sales->filter(function($sale){
			if ($sale->expires_at) {
				if ($sale->expires_at <= Carbon::now()->format('Y-m-d H:i:s')) {
					$sale->delete();
					return false;	
				}
				
			}
			return true;
		})->reduce(function($carry  , $sale){
			return $carry + $sale->percentage;
		});
	}
}
