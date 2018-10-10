<?php

namespace SecTheater\Marketplace\Models;

use Illuminate\Database\Eloquent\Builder;
use SecTheater\Marketplace\Repositories\Traits\HasSale;

class EloquentCategory extends Eloquent {
	use HasSale;
	protected $table = 'categories';

	public function products() {
		return $this->belongsToMany($this->productModel,'category_product','category_id','product_id');
	}
	public function sales() {
		return $this->morphMany($this->saleModel, 'saleable','saleable_type');
	}

	public function scopeParents(Builder $builder) {
		return $builder->whereNull('parent_id');
	}
	public function children() {
		return $this->hasMany(self::class, 'parent_id', 'id');
	}
	public function getDiscount()
	{
		return $this->sales->reduce(function ($carry , $sale){
			return $carry + $sale->percentage;
		});
	}
}
