<?php

namespace SecTheater\Marketplace\Models;

use Illuminate\Database\Eloquent\Relations\Relation;
class EloquentSale extends Eloquent {
    protected $casts = ['active' => 'boolean'];
    protected $table = 'sales';
    public function saleable() {
		return $this->morphTo();
	}
}
