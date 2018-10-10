<?php

return [
    'product' => [
        'review' => true,
    ],
    'cart' => [
        'tax' => [
            'enabled' => true,
            'percentage' => 10
        ],
    ],

    'models' => [
        'namespace' => '\\App\\',
        'user' => [

        ],
        'package' => [
            'cart' => SecTheater\Marketplace\Models\EloquentCart::class,
            'category' => \SecTheater\Marketplace\Models\EloquentCategory::class,
            'coupon' => \SecTheater\Marketplace\Models\EloquentCoupon::class,
            'product' => \SecTheater\Marketplace\Models\EloquentProduct::class,
            'product_variation' => \SecTheater\Marketplace\Models\EloquentProductVariation::class,
            'product_variation_type' => \SecTheater\Marketplace\Models\EloquentProductVariationType::class,
            'role' => \SecTheater\Marketplace\Models\EloquentRole::class,
            'sale' => \SecTheater\Marketplace\Models\EloquentSale::class,
            'user' => \SecTheater\Marketplace\Models\EloquentUser::class,
            'wishlist' => \SecTheater\Marketplace\Models\EloquentWishlist::class
        ]
    ],
    'observers' => [
        'register' => true,
    ],
    'repositories' => [
        'namespace' => '\App\Repositories',
        'user'      => [
        ],
    ],


];