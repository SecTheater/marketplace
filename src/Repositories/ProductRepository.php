<?php

namespace SecTheater\Marketplace\Repositories;

use Illuminate\Support\Collection;
use SecTheater\Marketplace\Contracts\ProductInterface;
use SecTheater\Marketplace\Exceptions\PropertyIsNotEnabledExpcetion;
use SecTheater\Marketplace\Models\EloquentProduct;

class ProductRepository extends Repository implements ProductInterface {
	protected $model, $typeRepo, $variationRepo, $category, $baseQuery, $criteria = [];
	public function __construct(EloquentProduct $model) {
		$this->model = $model;
		$this->category = app('CategoryRepository');
		$this->variationRepo = app('ProductVariationRepository');
		$this->typeRepo = app('ProductVariationTypeRepository');
	}
	public function generate(array $data) {
		$category = $this->category->generate($data['category']);
		$product = $this->model->firstOrCreate(array_except($data, ['details', 'category', 'type']));
		$type = $this->typeRepo->firstOrCreate($data['type'], [
			'product_id' => $product->id,
		]);
		$variation = $this->variationRepo->firstOrCreate(['product_id' => $product->id, 'product_variation_type_id' => $type->id], [
			'details' => $data['details'],
		]);
		$product->categories()->attach($category);
		return $product;
	}

	public function approve($id) {
		throw_unless(config('market.product.review'), PropertyIsNotEnabledExpcetion::class);
		return !!$this->model->findOrFail($id)->update(['reviewed_at' => date('Y-m-d H:i:s'), 'reviewed_by' => auth()->id()]);
	}
	public function fetchByCategories($types) {
		return ($this->model)->whereHas('categories', function ($query) use ($types) {
			$query->whereIn('type', $this->getCollection($types));
		});
	}
	public function fetchByLocation($locations) {
		return ($this->model)->whereHas('owner', function ($query) use ($locations) {
			$query->whereIn('location', $this->getCollection($locations));
		});
	}
	public function fetchTrendyByLocation($locations) {
		return $this->fetchByLocation($locations)->withCount('carts')->orderBy('carts_count', 'desc');
	}
	protected function availableCriteria() {
		return array_merge( [
			'locations' => 'fetchTrendyByLocation',
			'categories' => 'fetchByCategories',
		], $this->criteria);
	}
	public function addCriterion($name , $callback){
		$this->criteria[$name] =  $callback;
	}
	public function fetchByVariations($criteria) {
		if (array_key_exists('variations', $criteria)) {
			$variations = $criteria['variations'];
			array_forget($criteria, 'variations');
		}
		foreach ($criteria as $key => $criterion) {
			if (array_key_exists($key, $this->availableCriteria())) {
				if (is_callable($this->availableCriteria()[$key])) {
					$this->baseQuery = call_user_func_array($this->availableCriteria()[$key], $criterion);
					continue;
				}
				$this->baseQuery = call_user_func_array([$this, $this->availableCriteria()[$key]], (array) $criterion);

			}
		}
		return (isset($variations)) ? $this->variationRepo->contains($this->baseQuery, $variations) : $this->baseQuery->get();
	}
	protected function getCollection($collection) {
		if (!$collection instanceof Collection) {
			$collection = collect($collection);
		}
		return $collection;
	}
}
