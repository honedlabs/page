<?php

declare(strict_types=1);

use Honed\Page\Tests\Stubs\Product;
use Honed\Page\Tests\Stubs\Status;
use Honed\Page\Tests\TestCase;
use Illuminate\Support\Str;

uses(TestCase::class)->in(__DIR__);

function product(?string $name = null): Product
{
    return Product::create([
        'public_id' => Str::uuid(),
        'name' => $name ?? fake()->unique()->word(),
        'description' => fake()->sentence(),
        'price' => fake()->randomNumber(4),
        'best_seller' => fake()->boolean(),
        'status' => fake()->randomElement(Status::cases()),
        'created_at' => now()->subDays(fake()->randomNumber(2)),
    ]);
}

/**
 * Get the URIs registered by the stubs.
 */
function registered()
{
    return [
        '/',
        'products',
        'products/all',
        'products/variants',
    ];
}
