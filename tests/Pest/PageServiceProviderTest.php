<?php

declare(strict_types=1);

use Honed\Page\Facades\Page;
use Honed\Page\PageServiceProvider;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Request;

beforeEach(function () {
    Route::clearResolvedInstance('router');
    Page::path(\realpath('tests/Stubs/js/Pages'));
    Page::flushExcept();
    Page::flushOnly();
});
it('registers router macro', function () {
    Route::pages();

    expect(Route::getRoutes()->get(Request::METHOD_GET))
        ->toBeArray()
        ->toHaveCount(\count(registered()) + 1);
});