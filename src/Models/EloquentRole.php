<?php

namespace SecTheater\Marketplace\Models;

class EloquentRole extends Eloquent
{
    protected $casts = ['permissions' => 'array'];
    protected $table = 'roles';
    public function users()
    {
        return $this->belongsToMany($this->userModel,'role_user','role_id','user_id');
    }
}