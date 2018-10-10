<?php

return [
    'review' => true,
    'tax' => [
        'enabled' => true,
        'percentage' => 10
    ],
    'polymorphic' => [
        'category' => \SecTheater\Marketplace\Models\Category::class,
        'type' => \SecTheater\Marketplace\Models\ProductVariationType::class,
    ]

];