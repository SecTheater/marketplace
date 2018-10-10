<?php

namespace SecTheater\Marketplace\Repositories;

use SecTheater\Marketplace\Models\EloquentUser;

class UserRepository extends Repository {
	protected $model;

	public function __construct(EloquentUser $model) {
		$this->model = $model;
	}
}
