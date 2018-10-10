<?php

namespace SecTheater\Marketplace\Models;

class EloquentProductVariation extends Eloquent {
	protected $guarded = ['id'];
	protected $casts = ['details' => 'array'];
    protected $table = 'product_variations';

	public function type() {
		return $this->belongsTo($this->productVariationTypeModel, 'product_variation_type_id');
	}
	public function product() {
		return $this->belongsTo($this->productModel,'product_id');
	}
	public function getPriceAttribute() {
		return $this->type->price ?? $this->product->price;
	}
	public function getStockAttribute() {
		return $this->type->stock;
	}
}
