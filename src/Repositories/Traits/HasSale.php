<?php
namespace SecTheater\Marketplace\Repositories\Traits;

trait HasSale {
	public function hasSales() {
		return !! $this->getSalesCount();
	}
    public function getSalesCount()
    {
        return $this->sales->count();
    }
    abstract public function getDiscount();
}