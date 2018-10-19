<?php
namespace SecTheater\Marketplace\Traits;

use SecTheater\Marketplace\Contracts\CartInterface;
use SecTheater\Marketplace\Contracts\UserInterface;
use SecTheater\Marketplace\Exceptions\InsufficientProductQuantity;
use SecTheater\Marketplace\Exceptions\ProductAttributesDoesNotMatchException;
use SecTheater\Marketplace\Exceptions\ProductDoesNotExist;
use SecTheater\Marketplace\Repositories\Traits\HasStock;

trait CanBeCarted {
	use HasStock;
	protected $subtotal;
	public function create(array $attributes) {
		return auth()->user()->{$this->getModelName}()->create($attributes);
	}
	public function canBeAdded(int $id, int $quantity = 1) {
		return ($this->typeRepo->stock($id) >= $quantity) && $quantity;
	}
	public function addOrCreate($type, int $quantity = 1) {
		return $this->add($type, $quantity, true);
	}
	public function add($type, int $quantity = 1, $create = false) {
		throw_unless($this->canBeAdded($type->id, $quantity), InsufficientProductQuantity::class);
		if ($this->getModelName != 'wishlist') {
			$this->typeRepo->decrementStock($type, $quantity);
		}
		if ($create) {
			$attributes = ['product_id' => $type->product_id, 'quantity' => $quantity, 'product_variation_type_id' => $type->id];
			return $this->create($attributes);
		}
		$cart = auth()->user()->{$this->getModelName}()->where(['product_id' => $type->product_id , 'product_variation_type_id' => $type->id ])->firstOrFail();
		$cart->increment('quantity', $quantity);
		return $cart;
	}
	public function remove($id) {
		$cart = $this->item($id);
		$this->typeRepo->incrementStock($cart->type, $cart->quantity);
		return !!$cart->delete();
	}
	public function subtotal() {
		return $this->subtotal = auth()->user()->{$this->getModelName}()->get()->reduce(function ($carry, $cart) {
			$price = $cart->quantity * ($cart->type->price ?? $cart->product->price);
			if ($cart->product->has_sale) {
				$price -= $price * ($cart->product->sale / 100);
			}
			$carry += $price;
			return $carry;
		}, $this->subtotal = 0);
	}
	public function subtotalAfterCoupon($coupons) {
		return $this->subtotal = app('CouponRepository')->appliedCoupons($coupons)->reduce(function ($carry, $coupon) {
			return $carry -= $carry * ($coupon->percentage / 100);
		}, $this->subtotal());
	}
	public function total($coupons = null) {
		if (!$this->subtotal) {
			$this->subtotal = $coupons ? $this->subtotalAfterCoupon($coupons) : $this->subtotal();
		}

		if (config('market.cart.tax.enabled') && is_int(config('market.cart.tax.percentage')) && class_basename($this->model) == 'Cart') {
			$this->subtotal -= $this->subtotal * (config('market.cart.tax.percentage')) / 100;
		}
		return $this->subtotal;
	}
	public function items() {
		return auth()->user()->{$this->getModelName};
	}
	public function item(int $id = null, array $attributes = null , $connector = 'or') {
		if (!$id && !$attributes) {
			return $this->items();
		}
		if (!$id && $attributes) {
			return auth()->user()->{$this->getModelName}()->whereHas('product.variations', function($query) use($attributes , $connector){
				array_walk($attributes, function($value,$key) use($query , $connector){
					$query->where('details->' . $key , '=', $value , $connector);
				});
			})->get();
		}
		$cart = auth()->user()->{$this->getModelName}()->findOrFail($id);
		if ($attributes) {
			throw_unless($this->variationRepo->contains($cart->product_variation_type_id, $attributes), ProductAttributesDoesNotMatchException::class,'There is no product with the specified specifications.');
		}
		return $cart;
	}
	public function clearAll(UserInterface $user = null) {
		$user = $user ?? auth()->user();
		if ($this->getModelName == 'wishlist') {
			$released = $user->{$this->getModelName}()->count();
			$user->{$this->getModelName}()->detach();
			return $released;
		}

		$released = 0;
		$user->{$this->getModelName}->each(function ($item) use (&$released) {
			$released += $item->quantity;
			$item->type->increment('stock', $item->quantity);
		});
		return $released;
	}
	public function clearFor(UserInterface $user) {
		return $this->clearAll($user);
	}
	public function renew(CartInterface $cart, array $data) {
		if (isset($data['product_variation_type_id'] , $data['product_id'])) {
			$createdCart = $this->addOrCreate(
				$this->typeRepo->findOrFail(
					$data['product_variation_type_id']
				),
				$data['quantity'] ?? $cart->quantity
			);
			$this->remove($cart->id);
			return $createdCart;
		}
		throw_unless($this->canBeAdded($cart->product_id, $data['quantity'] ?? $cart->quantity), InsufficientProductQuantity::class);
		if (isset($data['quantity']) && $cart->quantity > $data['quantity']) {
			$this->typeRepo->incrementStock($cart->type, $cart->quantity - $data['quantity']);
			
		}
		if (isset($data['quantity']) && $cart->quantity < $data['quantity']) {
			$this->typeRepo->decrementStock($cart->type, $data['quantity'] - $cart->quantity);
		}
		$cart->update($data);
		return $cart;
	}
	public function stock($cart) {
		$base = 'SecTheater\\Marketplace\\Models\\' . class_basename($this->model);
		if (!$cart instanceof $base) {
			$cart = $this->findOrFail($cart);
		}
		return $cart->quantity;
	}

	public function __set($key, $value) {
		$this->{$key} = str_replace('eloquent','' ,strtolower(class_basename($value)));
	}
	public function __get($key) {
		if (!property_exists($this, $key)) {
			$this->{$key} = $this->model;
		}
		return $this->{$key};
	}
}
