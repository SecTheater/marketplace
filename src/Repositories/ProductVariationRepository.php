<?php

namespace SecTheater\Marketplace\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use SecTheater\Marketplace\Models\EloquentProductVariation;

class ProductVariationRepository extends Repository {
	protected $model;

	public function __construct(EloquentProductVariation $model) {
		$this->model = $model;
	}
	public function contains($collection, $attributes, bool $includePartials = true) {
		// Until Json Contains works in laravel.
		if ($collection instanceof Builder) {
			$collection = $collection->join('product_variations', 'products.id', '=', 'product_variations.product_id')->select('product_variations.product_variation_type_id')->pluck('product_variation_type_id');
		}
		if ($collection instanceof Collection) {
			$collection = $collection->pluck('id');
		} else {
			$collection = collect($collection);
		}
		$filteredProducts = collect();
		$products = $this->model->whereIn('product_variation_type_id', $collection)->get()->filter(function ($variation) use ($attributes, &$filteredProducts) {
			$diff = array_diff_assoc($variation->details, $attributes);
			// none of the provided matches.
			if (count($variation->details) == count($diff)) {
				return false;
			}
			// identical.
			if (!count($diff)) {
				return true;
			}
			// provided keys match, but values don't match (partially matching).
			if (array_has($variation->details, array_keys($attributes))) {
				$filteredProducts->push($variation);
			}
			return false;
		});
		if (count($products) && $filteredProducts && $includePartials) {
			return $products->merge($filteredProducts);
		}
		return count($products) ? $products : $filteredProducts;
	}
}