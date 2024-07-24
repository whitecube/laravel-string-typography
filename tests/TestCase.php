<?php

namespace Whitecube\Strings\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Whitecube\Strings\ServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }
}
