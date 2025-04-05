<?php

declare(strict_types=1);

namespace Honed\Page;

use Honed\Page\Facades\Page;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class PageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        /**
         * Register the pages macro for file-based routing.
         */
        Router::macro('pages', function (
            ?string $directory = null,
            string $uri = '/',
            string|false $name = 'pages',
        ): void {
            Page::create($directory, $uri, $name);
        });
    }
}
