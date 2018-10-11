<?php

namespace SecTheater\Marketplace\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use SecTheater\Marketplace\Models\EloquentUser as User;

class UserPolicy
{
    use HandlesAuthorization;
	public function downgrade(User $user)
	{
		return $user->hasRole('downgrade-user');
	}
	public function upgrade(User $user)
	{
		return $user->hasRole('upgrade-user');
	}

    public function view(User $user)
    {
        return $user->hasRole('view-user');
    }

    public function create(User $user)
    {
        return $user->hasRole('create-user');
    }

    public function update(User $user)
    {
        return $user->hasRole('update-user') || $user->user_id == $user->id;
    }

    public function delete(User $user)
    {
        return $user->hasRole('delete-user') || $user->user_id == $user->id;
    }
}
