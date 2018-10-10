<?php

namespace SecTheater\Marketplace\Models;

use SecTheater\Marketplace\Contracts\ProductInterface;
use SecTheater\Marketplace\Repositories\Traits\HasSale;

class EloquentProduct extends Eloquent implements ProductInterface {
	use HasSale;
	protected $sale = 0;
    protected $table = 'products';

	public function sales() {
		return $this->morphMany($this->saleModel, 'saleable')->whereActive(true);
	}
	public function categories() {
		return $this->belongsToMany($this->categoryModel,'category_product','product_id','category_id');
	}
	public function variations() {
		return $this->hasMany($this->productVariationModel,'product_id')->orderBy('order', 'asc');
	}
	public function types() {
		return $this->hasMany($this->productVariationTypeModel,'product_id');
	}
	public function carts() {
		return $this->hasMany($this->cartModel,'product_id');
	}
	public function wishlists() {
		return $this->hasMany($this->wishlistModel,'product_id');
	}
	public function owner() {
		return $this->belongsTo($this->userModel, 'user_id', 'id');
	}
	public function shouldBeReviewed() {
		return !!config('product.review');
	}
	
	public function discounts() {
		if (!$this->sale) {
			$this->sale = $this->categories->reduce(function($carry, $category){
			return $carry + $category->getDiscount();
		}) + $this->types->reduce(function($carry , $type){
			return $carry + $type->getDiscount();
		}) + ($this->sales()->latest()->first()->percentage ?? 0);
		}
		return $this->sale;
	}
	public function getHasSaleAttribute() {
		return $this->discounts() > 0;
	}
	public function getSaleAttribute() {
		if ($this->has_sale) {
			return $this->sale;
		}
		return 0;
	}
}