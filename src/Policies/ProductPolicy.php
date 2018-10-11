<?php

namespace SecTheater\Marketplace\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use SecTheater\Marketplace\Models\EloquentProduct as Product;
use SecTheater\Marketplace\Models\EloquentUser as User;

class ProductPolicy
{
    use HandlesAuthorization;
	public function rate(User $user)
	{
		return $user->hasRole('rate-product');
	}
	public function review(User $user, Product $product)
	{
		return $user->hasRole('review-product') && $product->shouldBeReviewed();
	}

    public function view(User $user, Product $product)
    {
        return $user->hasRole('view-product');
    }

    public function create(User $user)
    {
        return $user->hasRole('create-product');
    }

    public function update(User $user, Product $product)
    {
        return $user->hasRole('update-product') || $product->user_id == $user->id;
    }

    public function delete(User $user, Product $product)
    {
        return $user->hasRole('delete-product') || $product->user_id == $user->id;
    }
}
