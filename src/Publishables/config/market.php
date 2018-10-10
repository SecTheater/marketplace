<?php

return [
    'review' => true,
    'cart_expiration' => 10,
    'tax' => [
        'enabled' => true,
        'percentage' => 10
    ],
    'polymorphic' => [
        'category' => \SecTheater\Marketplace\Models\Category::class,
        'type' => \SecTheater\Marketplace\Models\ProductVariationType::class,
    ]

];