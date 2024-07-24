<?php

use Whitecube\Strings\ServiceProvider;

it('can load ServiceProvider correctly', function() {
    expect(app()->providerIsLoaded(ServiceProvider::class))->toBeTrue();
});
