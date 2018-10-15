<?php

namespace SecTheater\Marketplace\Tests\Unit\Integration\Observers;

use SecTheater\Marketplace\Models\EloquentProduct;
use SecTheater\Marketplace\Models\EloquentUser;
use SecTheater\Marketplace\Tests\TestCase;

class ProductObserverTest extends TestCase {
    /** @test */
    public function it_fires_creating_event_on_creating_a_product()
    {
        \Event::fake();
        $user = factory(EloquentUser::class)->create();
        $product = factory(EloquentProduct::class)->create([
            'user_id' => $user->id
        ]);
        \Event::assertDispatched('eloquent.creating: SecTheater\Marketplace\Models\EloquentProduct');
    }
    /** @test */
    public function it_fires_updating_event_on_updating_a_product()
    {
        \Event::fake();
        $user = factory(EloquentUser::class)->create();
        $product = factory(EloquentProduct::class)->create([
            'user_id' => $user->id
        ]);
        $product->update([
            'price' => 10
        ]);
        \Event::assertDispatched('eloquent.updating: SecTheater\Marketplace\Models\EloquentProduct');
    }
}
