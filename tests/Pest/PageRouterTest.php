<?php

declare(strict_types=1);

use Honed\Page\PageRouter;
use Honed\Page\Facades\Page;
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

    ensureRoutesExist(registered(), Request::METHOD_GET);

    ensureRoutesExist(registered(), Request::METHOD_HEAD);
});

it('creates routes by subdirectory', function () {
    Page::create('Products');

    ensureRoutesExist([
        '/',
        'all',
        'variants',
    ]);
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

    ensureRoutesExist([
        'products/all'
    ]);

    ensureRoutesDoNotExist([
        '/',
        'products',
        'products/variants',
    ]);
});

it('excludes all directories', function () {
    Page::except('/');

    Page::create();

    ensureRoutesExist(['/']);

    ensureRoutesDoNotExist([
        'products',
        'products/all',
        'products/variants',
    ]);
});

it('excludes directories', function () {
    Page::except('**/Variants/*');

    Page::create();

    ensureRoutesExist([
        '/',
        'products/all',
        'products',
    ]);

    ensureRoutesDoNotExist([
        'products/variants',
    ]);
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

    ensureRoutesExist([
        '/',
        'products',
        'products/variants',
    ]);

    ensureRoutesDoNotExist([
        'products/all',
    ]);
});