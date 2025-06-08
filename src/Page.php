<?php

declare(strict_types=1);

namespace Honed\Page;

use Illuminate\Support\Str;

use function array_map;
use function explode;
use function implode;
use function pathinfo;
use function rtrim;
use function trim;

class Page
{
    /**
     * The name of the page.
     *
     * @var string
     */
    protected $name;

    /**
     * The path to the page from the pages directory as configured by
     * createInertiaApp().
     *
     * @var string
     */
    protected $path;

    /**
     * The uri of the page to register.
     *
     * @var string
     */
    protected $uri;

    /**
     * Create a new page instance.
     *
     * @param  string  $filename
     */
    public function __construct($filename)
    {
        $directory = pathinfo($filename, PATHINFO_DIRNAME);

        $this->name = pathinfo($filename, PATHINFO_FILENAME);

        $this->path = trim(rtrim($directory, './').'/'.$this->name, './');

        $this->uri = implode('/', array_map(
            Str::kebab(...),
            explode('/', $this->path)
        ));
    }

    /**
     * Get the name of the page.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the path to the file.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get the uri this page should be located at.
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Get the route name for the page.
     *
     * @return string
     */
    public function getRouteName()
    {
        return implode('.', array_map(
            Str::kebab(...),
            explode('/', $this->getPath())
        ));
    }
}
