<?php
namespace SecTheater\Marketplace\Repositories\Traits;

trait HasSale {
	public function hasSales() {
		return $this->sales->count();
	}
	public function getTotalSale() {
		if ($this->hasSales()) {
			return $this->sales()->whereActive(true)->sum('percentage');
		}
		return 0;
	}
}