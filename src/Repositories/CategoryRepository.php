<?php

namespace SecTheater\Marketplace\Repositories;

use SecTheater\Marketplace\Models\EloquentCategory;

class CategoryRepository extends Repository {
	protected $model;

	public function __construct(EloquentCategory $model) {
		$this->model = $model;
	}
	public function generate($type) {
		return $this->model->firstOrCreate(compact('type'));
	}
}