<?php

namespace SecTheater\Marketplace\Repositories;

use SecTheater\Marketplace\Models\EloquentSale;


class SaleRepository extends Repository {
    protected $model;

    public function __construct(EloquentSale $model) {
        $this->model = $model;
    }
}
