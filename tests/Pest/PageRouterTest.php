<?php

declare(strict_types=1);

use Honed\Page\Facades\Page;
use Honed\Page\PageRouter;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Request;

beforeEach(function () {
    $this->path = \realpath('tests/Stubs/js/Pages');
    Route::clearResolvedInstance('router');
    Page::path($this->path);
    Page::flushExcept();
    Page::flushOnly();
});

it('has path', function () {
    $router = Page::getFacadeRoot();

    expect($router)
        ->getPath()->toBe($this->path);

    $router->flushPath();

    // Should look at the inertia config if none exists
    expect($router)
        ->getPath()->toBe(config('inertia.testing.page_paths'));

    // Should default to the resource path if nothing else is found
    config()->set('inertia.testing.page_paths', null);

    expect($router)
        ->getPath()->toBe(resource_path('js/Pages'));

    // Should allow you to set the path manually
    $path = resource_path('js/pages');
    $router->path($path);

    expect($router)
        ->getPath()->toBe($path);
});

it('creates routes', function () {
    Page::create();

    $gets = Route::getRoutes()->get(Request::METHOD_GET);

    // dd(($gets['products/variants'])->getName());

    expect($gets)
        ->toBeArray()
        ->toHaveCount(\count(registered()))
        ->toHaveKeys(registered());

    foreach (registered() as $route) {
        expect($gets[$route])
            ->toBeInstanceOf(RoutingRoute::class)
            ->getAction()->scoped(fn ($action) => $action
                ->toBeArray()
                ->toHaveKey('uses')
                ->{'uses'}->toBeInstanceOf(\Closure::class)
            );
    }

    $heads = Route::getRoutes()->get(Request::METHOD_HEAD);

    expect($heads)
        ->toBeArray()
        ->toHaveCount(\count(registered()))
        ->toHaveKeys(registered());

    foreach (registered() as $route) {
        expect($heads[$route])
            ->toBeInstanceOf(RoutingRoute::class)
            ->getAction()->scoped(fn ($action) => $action
                ->toBeArray()
                ->toHaveKey('uses')
                ->{'uses'}->toBeInstanceOf(\Closure::class)
            );
    }
});

it('creates routes by subdirectory', function () {
    Page::create('Products');

    $gets = Route::getRoutes()->get(Request::METHOD_GET);

    $routes = \array_reduce(
        registered(),
        function ($routes, $route) {
            if ($route !== '/') {
                $new = \str_replace('products', '', $route);

                if (empty($new)) {
                    $new = '/';
                } else {
                    $new = ltrim($new, '/');
                }

                $routes[] = $new;
            }

            return $routes;
        },
        []
    );

    expect($gets)
        ->toBeArray()
        ->toHaveCount(\count($routes));

    foreach ($routes as $route) {
        expect($gets[$route])
            ->toBeInstanceOf(RoutingRoute::class)
            ->getAction()->scoped(fn ($action) => $action
                ->toBeArray()
                ->toHaveKey('uses')
                ->{'uses'}->toBeInstanceOf(\Closure::class)
            );
    }
});

it('fails if the directory does not exist', function () {
    Page::create('NonExistent');
})->throws(\Error::class);

it('fails if a file is provided', function () {
    Page::create('Index.vue');
})->throws(\Error::class);

it('excludes patterns', function () {
    expect(Page::getFacadeRoot())
        ->hasExcept()->toBeFalse()
        ->getExcept()->scoped(fn ($except) => $except
            ->toBeArray()
            ->toBeEmpty()
        )
        ->except('Index')->toBeInstanceOf(PageRouter::class)
        ->hasExcept()->toBeTrue()
        ->getExcept()->toEqual(['Index']);

    Page::create();

    $gets = Route::getRoutes()->get(Request::METHOD_GET);

    expect($gets)
        ->toBeArray()
        ->toHaveCount(\count(registered()) - 3);
});

it('excludes all directories', function () {
    Page::except('/');

    Page::create();

    $gets = Route::getRoutes()->get(Request::METHOD_GET);

    expect($gets)
        ->toBeArray()
        ->toHaveCount(1 + 1);

    Page::create('Products');

    $gets = Route::getRoutes()->get(Request::METHOD_GET);

    expect($gets)
        ->toBeArray()
        ->toHaveCount(1 + 2);
});

it('excludes directories', function () {
    Page::except('**/Variants/*');

    Page::create();

    $gets = Route::getRoutes()->get(Request::METHOD_GET);
    
    expect($gets)
        ->toBeArray()
        ->toHaveCount(3);
});

it('includes patterns', function () {
    expect(Page::getFacadeRoot())
        ->hasOnly()->toBeFalse()
        ->getOnly()->scoped(fn ($only) => $only
            ->toBeArray()
            ->toBeEmpty()
        )
        ->only('Index')->toBeInstanceOf(PageRouter::class)
        ->hasOnly()->toBeTrue()
        ->getOnly()->toEqual(['Index']);

    Page::create();

    $gets = Route::getRoutes()->get(Request::METHOD_GET);

    expect($gets)
        ->toBeArray()
        ->toHaveCount(3);
});