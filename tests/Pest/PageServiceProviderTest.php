<?php

declare(strict_types=1);

use Honed\Page\Facades\Page;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    Route::clearResolvedInstance('router');
    Page::path(\realpath('tests/Stubs/js/Pages'));
    Page::flushExcept();
    Page::flushOnly();
});
it('registers router macro', function () {
    Route::pages();

    ensureRoutesExist(registered());
});