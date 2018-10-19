<?php

namespace SecTheater\Marketplace\Tests\Unit\Integration\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use SecTheater\Marketplace\Exceptions\CouponCanNotBePurchasedException;
use SecTheater\Marketplace\Exceptions\CouponExpiredException;
use SecTheater\Marketplace\Models\EloquentUser as User;
use SecTheater\Marketplace\Tests\TestCase;
class UserRepositoryTest extends TestCase {
    public function setUp() {
        parent::setUp();
        $this->userRepo = app('UserRepository');
    }
    /** @test */
    public function it_gives_user_a_role()
    {
        $this->actingAs(factory(User::class)->create());
        $this->userRepo->giveRole('admin');
        $this->assertEquals('admin',auth()->user()->roles->first()->slug);
    }
}