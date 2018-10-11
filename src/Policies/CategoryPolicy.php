<?php

namespace SecTheater\Marketplace\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use SecTheater\Marketplace\Models\EloquentUser as User;
use SecTheater\Marketplace\Models\EloquentCategory as Category;
class CategoryPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Category $category)
    {
        return $user->hasRole('view-category');
    }

    public function create(User $user)
    {
        return $user->hasRole('create-category');
    }

    public function update(User $user, Category $category)
    {
        return $user->hasRole('update-category') || $category->user_id == $user->id;
    }

    public function delete(User $user, Category $category)
    {
        return $user->hasRole('delete-category') || $category->user_id == $user->id;
    }
}
