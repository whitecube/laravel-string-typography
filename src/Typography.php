<?php

namespace Whitecube\Strings;

use Closure;

class Typography
{
    /**
     * The provided string to transform.
     */
    protected string $value;

    /**
     * The provided string to transform.
     */
    protected static array $handlers = [];

    /**
     * Add a typographic rule handler.
     */
    static public function rule(string $key, string $regex, Closure $callback): void
    {
        static::$handlers[$key] = [$regex, $callback];
    }

    /**
     * Remove a typographic rule handler.
     */
    static public function remove(string $key): void
    {
        if(isset(static::$handlers[$key])) {
            unset(static::$handlers[$key]);
        }
    }

    /**
     * Return the requested handlers.
     */
    static protected function getHandlers(null|string|array $only = null, null|string|array $except = null): array
    {
        $only = ($only ? (is_array($only) ? $only : [$only]) : null);
        $except = ($except ? (is_array($except) ? $except : [$except]) : null);

        $handlers = match (true) {
            !is_null($only) => array_filter(static::$handlers, fn($key) => in_array($key, $only), ARRAY_FILTER_USE_KEY),
            !is_null($except) => array_filter(static::$handlers, fn($key) => !in_array($key, $except), ARRAY_FILTER_USE_KEY),
            default => static::$handlers,
        };

        return array_reduce($handlers, function($all, $handler) {
            [$regex, $callback] = $handler;
            $all[$regex] = $callback;
            return $all;
        }, []);
    }

    /**
     * Create a new Typography transformer instance
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * Run the value transformations.
     */
    public function handle(null|string|array $only = null, null|string|array $except = null): string
    {
        return preg_replace_callback_array(
            static::getHandlers(only: $only, except: $except),
            $this->value
        );
    }
}
