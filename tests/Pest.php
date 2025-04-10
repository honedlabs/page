<?php

declare(strict_types=1);

use Honed\Page\Tests\Stubs\Product;
use Honed\Page\Tests\Stubs\Status;
use Honed\Page\Tests\TestCase;
use Illuminate\Routing\Route;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Request;

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

/**
 * Ensure the routes exist for the given pages and method.
 *
 * @param  array<int, string>  $pages
 * @param  string  $method
 */
function ensureRoutesExist($pages = [], $method = Request::METHOD_GET)
{
    $routes = RouteFacade::getRoutes()->get($method);

    foreach ($pages as $page) {
        expect($routes[$page])
            ->toBeInstanceOf(Route::class)
            ->getAction()->scoped(fn ($action) => $action
            ->toBeArray()
            ->toHaveKey('uses')
            ->{'uses'}->toBeInstanceOf(\Closure::class)
            );
    }
}

/**
 * Ensure the routes do not exist for the given pages and method.
 *
 * @param  array<int, string>  $pages
 * @param  string  $method
 */
function ensureRoutesDoNotExist($pages = [], $method = Request::METHOD_GET)
{
    $routes = RouteFacade::getRoutes()->get($method);

    foreach ($pages as $page) {
        expect(Arr::has($routes, $page))->toBeFalse();
    }
}
