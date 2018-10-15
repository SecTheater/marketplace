<?php

namespace SecTheater\Marketplace\Tests\Unit\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use SecTheater\Marketplace\Models\EloquentRole;
use SecTheater\Marketplace\Tests\TestCase;

class RoleTest extends TestCase {
    public function setUp()
    {
        parent::setUp();
        $this->role = new EloquentRole;
    }
    /** @test */
    public function it_has_users_table()
    {
        $this->assertEquals('roles', $this->role->getTable());
    }
    /** @test */
    public function it_has_every_column_fillable_except_for_id()
    {
        $this->assertEquals(['id'] , $this->role->getGuarded());
    }
    /** @test */
    public function it_casts_permissions_to_array()
    {
        $this->role->permissions = ['something' => 'another'];
        $this->assertTrue(is_array($this->role->permissions));
    }
    /** @test */
    public function it_has_user_with_many_to_many_relationship()
    {
       $this->assertInstanceOf(BelongsToMany::class,$this->role->users());
       $this->assertEquals('role_user',$this->role->users()->getTable());
       $this->assertEquals('role_id',$this->role->users()->getForeignPivotKeyName());
       $this->assertEquals('user_id',$this->role->users()->getRelatedPivotKeyName());

    }

}
