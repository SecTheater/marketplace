<?php

namespace SecTheater\Marketplace\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use SecTheater\Marketplace\Models\EloquentRole;
use SecTheater\Marketplace\Models\EloquentUser as User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication,RefreshDatabase;
    public function setUp()
    {
        parent::setUp();
        $this->seed('RolesTableSeeder');
        $this->user = factory(User::class)->create();
        $this->user->roles()->attach(EloquentRole::first());
        $this->actingAs($this->user);
    }
    // Heveans only know why factory doesn't work while this does. :/
    protected function createSale(array $attributes)
    {
        $sale = new \SecTheater\Marketplace\Models\EloquentSale;
        $sale->fill($data = array_merge($attributes,[
            'user_id' => auth()->id(),
            'percentage' => 10.5
        ]))->save();
        return $sale;
    }

}
