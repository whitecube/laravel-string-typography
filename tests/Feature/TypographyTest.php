<?php

use Whitecube\Strings\ServiceProvider;
use Whitecube\Strings\Typography;

it('can load ServiceProvider correctly', function() {
    expect(app()->providerIsLoaded(ServiceProvider::class))->toBeTrue();
});

it('can replace breakable spaces in front of punctuation marks', function() {
    $cases = [
        '<p>This string contains a breakable exclamation ! And an unbreakable one too! Even a weird one &nbsp; ! Foo.</p>',
        '<p>This string contains a breakable question ? And an unbreakable one too? Even a weird one &nbsp; ? Foo.</p>',
        '<p>This string contains a breakable colon : And an unbreakable one too: Even a weird one &nbsp; : Foo.</p>',
        '<p>This string contains a breakable semicolon ; And an unbreakable one too; Even a weird one &nbsp; ; Foo.</p>',
    ];

    $expectations = [
        '<p>This string contains a breakable exclamation&nbsp;! And an unbreakable one too! Even a weird one&nbsp;! Foo.</p>',
        '<p>This string contains a breakable question&nbsp;? And an unbreakable one too? Even a weird one&nbsp;? Foo.</p>',
        '<p>This string contains a breakable colon&nbsp;: And an unbreakable one too: Even a weird one&nbsp;: Foo.</p>',
        '<p>This string contains a breakable semicolon&nbsp;; And an unbreakable one too; Even a weird one&nbsp;; Foo.</p>',
    ];

    foreach ($cases as $i => $value) {
        expect((string) str($value)->typography())->toBe($expectations[$i]);
    }
});

it('can replace horizontal ellipsises with according HTML entity', function() {
    $cases = [
        '<p>This string&#8230; contains an &#8230; ellipsis &#8230;at multiple locations&#8230;</p>',
        '<p>This string&#x2026; contains an &#x2026; ellipsis &#x2026;at multiple locations&#x2026;</p>',
        '<p>This string... contains an ... ellipsis ...at multiple locations...</p>',
        '<p>This string… contains an … ellipsis …at multiple locations…</p>',
    ];

    $expectation = '<p>This string&hellip; contains an &hellip; ellipsis &hellip;at multiple locations&hellip;</p>';

    foreach ($cases as $value) {
        expect((string) str($value)->typography())->toBe($expectation);
    }
});

it('can register and remove custom typographic rules', function() {
    Typography::rule(
        key: 'a-to-b',
        regex: '/a/',
        callback: fn(array $matches) => 'b',
    );

    expect((string) str('...Banana !')->typography())
        ->toBe('&hellip;Bbnbnb&nbsp;!');

    Typography::remove('a-to-b');

    expect((string) str('...Banana !')->typography())
        ->toBe('&hellip;Banana&nbsp;!');
});

it('can execute one of the declared rules', function() {
    expect((string) str('...Banana !')->typography(only: 'hellip'))
        ->toBe('&hellip;Banana !');
});

it('can execute some of the declared rules', function() {
    Typography::rule(
        key: 'a-to-b',
        regex: '/a/',
        callback: fn(array $matches) => 'b',
    );

    expect((string) str('...Banana !')->typography(only: ['hellip','unbreakable-punctuation']))
        ->toBe('&hellip;Banana&nbsp;!');

    Typography::remove('a-to-b');
});

it('can execute all of the declared rules except one', function() {
    Typography::rule(
        key: 'a-to-b',
        regex: '/a/',
        callback: fn(array $matches) => 'b',
    );

    expect((string) str('...Banana !')->typography(except: 'hellip'))
        ->toBe('...Bbnbnb&nbsp;!');
    
    Typography::remove('a-to-b');
});

it('can execute all of the declared rules except some', function() {
    Typography::rule(
        key: 'a-to-b',
        regex: '/a/',
        callback: fn(array $matches) => 'b',
    );

    expect((string) str('...Banana !')->typography(except: ['hellip','unbreakable-punctuation']))
        ->toBe('...Bbnbnb !');
    
    Typography::remove('a-to-b');
});
