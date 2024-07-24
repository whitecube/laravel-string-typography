<?php

namespace Whitecube\Strings;

use Illuminate\Support\ServiceProvider as Provider;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

class ServiceProvider extends Provider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        Typography::rule(
            key: 'unbreakable-punctuation',
            regex: '/(?:(?:&nbsp;)|\s)+([:!?;])/',
            callback: fn(array $matches) => '&nbsp;'.$matches[1],
        );
        Typography::rule(
            key: 'hellip',
            regex: '/(?:(?:\&\#8230\;)|(?:\&\#x2026\;)|(?:\.\.\.)|\…)/',
            callback: fn(array $matches) => '&hellip;',
        );
    }

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        Str::macro('typography', function (string $value) {
            return (new Typography($value))->handle();
        });
        Stringable::macro('typography', function () {
            return new static(Str::typography($this->value));
        });
    }
}
