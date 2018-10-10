<?php

namespace SecTheater\Marketplace\Repositories\Traits;

trait HasStock {
	public function inStock($id) {
		return $this->stock($id) > 5;
	}
	public function lowStock($id) {
		return ($this->stock($id) > 0 && $this->stock($id) < 5);
	}
	public function hasStock($id) {
		return $this->stock($id) > 0;
	}

	abstract function stock($cart);
}