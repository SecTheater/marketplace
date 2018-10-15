<?php

namespace SecTheater\Marketplace\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use SecTheater\Marketplace\Contracts\UserInterface;
use SecTheater\Marketplace\Traits\Roles\Roles;
class EloquentUser extends Eloquent implements UserInterface,
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract {
	use Roles,Authenticatable, Authorizable, CanResetPassword;

    protected $table = 'users';
    protected $casts = ['permissions' => 'array'];
	public function wishlist() {
		return $this->belongsToMany($this->wishlistModel, 'user_wishlist','user_id','wishlist_id');
	}
	public function cart() {
		return $this->belongsToMany($this->cartModel, 'cart_user','user_id','cart_id');
	}
	public function products() {
        if (config('market.product.review')) {
            return $this->hasMany($this->productModel, 'user_id', 'id')->where('products.reviewed_at', '!=', null);

        }
		return $this->hasMany($this->productModel, 'user_id', 'id');
	}
	public function coupons() {
		return $this->belongsToMany($this->couponModel, 'user_coupon', 'user_id', 'coupon_id')->withPivot('purchased');
	}
	public function roles()
	{
        return $this->belongsToMany($this->roleModel, 'role_user', 'user_id', 'role_id');
	}
}