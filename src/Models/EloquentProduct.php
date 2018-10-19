<?php

namespace SecTheater\Marketplace\Models;

use Carbon\Carbon;
use SecTheater\Marketplace\Contracts\ProductInterface;
use SecTheater\Marketplace\Repositories\Traits\HasSale;

class EloquentProduct extends Eloquent implements ProductInterface {
	use HasSale;
	protected $sale = 0;
    protected $table = 'products';

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
		return !!config('market.product.review');
	}
	protected function activeSalesFor($relation)
	{
		return $this->whereHas("$relation.sales",function($query){
			return $query->where('sales.expires_at' , '>' , Carbon::now()->format('Y-m-d H:i:s'));
		})->get();
	}
	protected function sumSalesFor($relation)
	{
		return $this->activeSalesFor($relation)->reduce(function($carry, $relation){
			return $carry + $relation->getDiscount();
		});
	}	
	public function activeSales()
	{
		return $this->sales()->where('expires_at' , '>' , Carbon::now()->format('Y-m-d H:i:s'))->orWhereNull('expires_at')->get();
	}
	public function getDiscount() {
		if (!$this->sale) {
			$this->sale = $this->sumSalesFor('categories') + $this->sumSalesFor('types') + $this->activeSales()->sum('percentage');
		}
		return $this->sale;
	}
	public function getHasSaleAttribute() {
		return $this->getDiscount() > 0;
	}
	public function getSaleAttribute() {
		if ($this->has_sale) {
			return $this->sale;
		}
		return 0;
	}
}