<?php

namespace SecTheater\Marketplace\Repositories;

use SecTheater\Marketplace\Contracts\CartInterface;
use SecTheater\Marketplace\Contracts\UserInterface;
use SecTheater\Marketplace\Models\EloquentWishlist as Wishlist;
use SecTheater\Marketplace\Traits\CanBeCarted;

class WishlistRepository extends Repository implements CartInterface {
	use CanBeCarted;
	protected $model, $typeRepo, $variationRepo;
	public function __construct(Wishlist $model) {
		$this->model = $model;
		$this->typeRepo = app('ProductVariationTypeRepository');
		$this->variationRepo = app('ProductVariationRepository');
	}
	public function pushWishToCart($wish, UserInterface $user = null) {
		if (!$wish instanceof Wishlist) {
			$wish = $this->findOrFail($wish);
		}
		$user = $user ?? auth()->user();
		$user->wishlist()->detach($wish);
    	try {
    		$this->getModelName = 'cart';
    		return $this->add($wish->type, $wish->quantity, true);
    	} catch (InsufficientProductQuantity $e) {
    		$user->wishlist()->attach($wish);
    		throw new InsufficientProductQuantity;
    	}
	}

}
