<?php

namespace PaulhenriL\LaravelRouteHelpers\Route;

use Illuminate\Routing\Route;
use Illuminate\Support\Str;
use PaulhenriL\LaravelRouteHelpers\Exceptions\NonRestfulRouteException;

class Info
{
    /**
     * PHP allowed function name regex.
     */
    protected const PHP_FUNCTION_REGEX = '/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*$/';

    /**
     * The route name.
     *
     * @var string
     */
    protected $routeName;

    /**
     * The route target action.
     *
     * @var string
     */
    protected $action;

    /**
     * The route target resource in plural form.
     *
     * @var string
     */
    protected $resourcePlural;

    /**
     * The route target resource in singular form.
     *
     * @var string
     */
    protected $resourceSingular;

    /**
     * The route nesting.
     *
     * @var string
     */
    protected $nesting;

    /**
     * The cached version of this route base helper name.
     *
     * @var string
     */
    protected $cachedBaseHelperName;

    /**
     * RouteInfo constructor.
     */
    public function __construct(Route $route)
    {
        $this->routeName = $route->getName();

        $parts = explode('.', $this->routeName);
        $this->action = array_pop($parts);
        $this->resourcePlural = array_pop($parts);

        $this->resourceSingular = $this->resourcePlural
            ? Str::singular($this->resourcePlural)
            : null;

        $this->nesting = array_reduce($parts, function ($acc, $part) {
            return $acc . Str::singular($part) . '_';
        }, '');
    }

    /**
     * Determine if valid for helper generation.
     */
    public function isValid()
    {
        return $this->resourcePlural != null
            && $this->action != null
            && $this->resourceSingular != null
            && $this->isRestful()
            && $this->hasOnlyAllowedChars();
    }

    /**
     * Is this route one of the 7 restful allowed routes.
     */
    public function isRestful()
    {
        return in_array($this->action, [
            'index',
            'create',
            'store',
            'show',
            'edit',
            'update',
            'destroy'
        ]);
    }

    /**
     * Return this route name.
     */
    public function getRouteName()
    {
        return $this->routeName;
    }

    /**
     * Return the helper base name for this route.
     */
    public function getHelperBaseName()
    {
        if (!$this->cachedBaseHelperName) {
            switch ($this->action) {
                case 'index':
                case 'store':
                    $baseHelperName = $this->nesting . $this->resourcePlural;
                    break;
                case 'create':
                    $baseHelperName = "new_{$this->nesting}{$this->resourceSingular}";
                    break;
                case 'edit':
                    $baseHelperName = "edit_{$this->nesting}{$this->resourceSingular}";
                    break;
                case 'show':
                case 'update':
                case 'destroy':
                    $baseHelperName = $this->nesting . $this->resourceSingular;
                    break;
                default:
                    throw new NonRestfulRouteException($this->routeName);
            }

            $this->cachedBaseHelperName = $baseHelperName;
        }

        return $this->cachedBaseHelperName;
    }

    /**
     * Will the function name be composed of chars that are allowed in php
     * function names?
     */
    protected function hasOnlyAllowedChars(): bool
    {
        return preg_match(
            static::PHP_FUNCTION_REGEX, $this->getHelperBaseName()
        );
    }
}
