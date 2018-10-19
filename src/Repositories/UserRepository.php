<?php

namespace SecTheater\Marketplace\Repositories;

use SecTheater\Marketplace\Contracts\UserInterface;
use SecTheater\Marketplace\Models\EloquentUser;

class UserRepository extends Repository {
	protected $model,$roleRepo;

	public function __construct(EloquentUser $model) {
		$this->model = $model;
        $this->roleRepo = app('RoleRepository');
	}
    public function giveRole($slug , UserInterface $user = null)
    {
        if (!$user && auth()->check()) {
            $user = auth()->user();
        }
        $role = $this->roleRepo->findbySlug($slug);
        $user->roles()->sync($role);
    }
}
