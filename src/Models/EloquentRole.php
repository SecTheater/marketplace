<?php

namespace SecTheater\Marketplace\Models;

class EloquentRole extends Eloquent
{
    protected $guarded = [];
    protected $casts = ['permissions' => 'array'];
    protected $table = 'roles';
    public function users()
    {
        return $this->belongsToMany($this->userModel);
    }

    public static function bySlug($slug)
    {
        return static::where('slug', $slug)->first();
    }
}