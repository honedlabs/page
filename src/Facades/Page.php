<?php

declare(strict_types=1);

namespace Honed\Page\Facades;

use Honed\Page\PageRouter;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Honed\Page\PageRouter path(string $path) Set the path where your pages are located.
 * @method static string getPath() Get the configured pages path
 * @method static \Honed\Page\PageRouter flushPath() Clear the pages path configuration
 * @method static \Honed\Page\PageRouter except(string|iterable<int, string> ...$patterns) Exclude files/folders from registration
 * @method static array<int,string> getExcept() Get excluded patterns
 * @method static bool hasExcept() Check if any exclude patterns are set
 * @method static \Honed\Page\PageRouter flushExcept() Clear excluded patterns
 * @method static \Honed\Page\PageRouter only(string|iterable<int, string> ...$patterns) Limit to specific files/folders
 * @method static array<int,string> getOnly() Get inclusion patterns
 * @method static bool hasOnly() Check if any inclusion patterns are set
 * @method static \Honed\Page\PageRouter flushOnly() Clear inclusion patterns
 * @method static void create(?string $directory = null, string $uri = '/', string|false $name = 'pages') Register pages as routes
 *
 * @see PageRouter
 */
class Page extends Facade
{
    /**
     * Get the root object behind the facade.
     *
     * @return PageRouter
     */
    public static function getFacadeRoot()
    {
        // @phpstan-ignore-next-line
        return parent::getFacadeRoot();
    }

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return PageRouter::class;
    }
}
