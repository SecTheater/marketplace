<?php
namespace SecTheater\Marketplace\Repositories;

use SecTheater\Marketplace\Contracts\CartInterface;
use SecTheater\Marketplace\Models\EloquentCart;
use SecTheater\Marketplace\Traits\CanBeCarted;

class CartRepository extends Repository implements CartInterface {
	use CanBeCarted;
	protected $model, $typeRepo, $variationRepo;
	public function __construct(EloquentCart $model) {
		$this->model = $model;
		$this->typeRepo = app('ProductVariationTypeRepository');
		$this->variationRepo = app('ProductVariationRepository');

	}
}