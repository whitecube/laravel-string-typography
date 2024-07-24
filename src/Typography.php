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
     * Remove a typographic rule handler.
     */
    static protected function getHandlers(): array
    {
        return array_reduce(static::$handlers, function($all, $handler) {
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
    public function handle(): string
    {
        return preg_replace_callback_array(
            static::getHandlers(),
            $this->value
        );
    }
}
