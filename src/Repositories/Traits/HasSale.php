<?php
namespace SecTheater\Marketplace\Repositories\Traits;

trait HasSale {
	public function sales() {
        return $this->morphMany($this->saleModel, 'saleable','saleable_type')->whereActive(true);
    }

    public function hasSales() {
		return !! $this->getSalesCount();
	}
    public function getSalesCount()
    {
        return $this->sales->count();
    }
    abstract public function getDiscount();
}