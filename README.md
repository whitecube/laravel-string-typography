# Typography Rules for Laravel

Rendering strings provided by users can result in unexpected typographic results. For instance, in most latin languages (such as french) it is recommended to add a non-breakable space in front of, amongst others, exclamation or question marks (`!` and `?`). Most users will probably just type a regular space, which could result in an unwanted line break just before these punctuation marks.

This simple package provides a `typography` macro for Laravel's `Str` facade and `Stringable` instances (created using `Str::of()` or `str()`) that will take care of these typographic details.

It is also possible to enhance this package by adding your own typographic rules.

```php
$content = 'Mama mia !';

echo str($content)->typography();
```
```
Mama mia&nbsp;!
```

## Installation

```bash
composer require whitecube/laravel-string-typography
```

## Getting started

The package's ServiceProvider and therefore its `typography` macro will automatically be registered upon installation. You can start using it right away:

```blade
{{-- Using the "Str" facade --}}
<h1>{!! Str::typography($title) !!}</h1>

{{-- Using the "str()" helper --}}
<p>{!! str($paragraph)->typography() !!}</p>
```

Using `Stringable` instances, you can chain `typography` with other helper methods:

```blade
<div>{!! str($text)->markdown()->typography() !!}</div>
```

> [!NOTE]  
> Since the transformed strings contain HTML entities (such as `&nbsp;`), don't forget to render them using `{!! !!}` instead of `{{ }}` in your Blade templates.

## Default typographic rules

| Key                       | Usage                                               | Description                                                                                   |
|:------------------------- |:--------------------------------------------------- |:--------------------------------------------------------------------------------------------- |
| `unbreakable-punctuation` | Remove unwanted line breaks in front of punctuation | Replaces ` !`, ` ?`, ` :`, ` ;` with `&nbsp;!`, `&nbsp;?`, `&nbsp;:`, `&nbsp;;` respectively. |
| `hellip`                  | Use the correct "horizontal ellipsis" HTML entity   | Replaces `&#8230;`, `&#x2026;`, `...`, `…` with `&hellip;`.                                   |

## Registering & removing typographic rules

```php
use Whitecube\Strings\Typography;

Typography::rule(
    key: 'a-to-b',
    regex: '/a/',
    callback: fn(array $matches) => 'b',
);
```

By default all the previously registered typographic rules will be applied when using the new `typography` method. It is possible to globally remove one of them using:

```php
use Whitecube\Strings\Typography;

Typography::remove('a-to-b');
```

## Filtering typographic rules

Call a single or a few specific rules:

```blade
<div>{!! str($text)->typography(only: 'hellip') !!}</div>
<div>{!! str($text)->typography(only: ['hellip', 'unbreakable-punctuation']) !!}</div>
```

Call all rules except a single or a few specific unwanted rules:

```blade
<div>{!! str($text)->typography(except: 'hellip') !!}</div>
<div>{!! str($text)->typography(except: ['hellip', 'unbreakable-punctuation']) !!}</div>
```

---

## 🔥 Sponsorships 

If you are reliant on this package in your production applications, consider [sponsoring us](https://github.com/sponsors/whitecube)! It is the best way to help us keep doing what we love to do: making great open source software.

## Contributing

Feel free to suggest changes, ask for new features or fix bugs yourself. We're sure there are still a lot of improvements that could be made, and we would be very happy to merge useful pull requests. Thanks!

## Made with ❤️ for open source

At [Whitecube](https://www.whitecube.be) we use a lot of open source software as part of our daily work.
So when we have an opportunity to give something back, we're super excited!

We hope you will enjoy this small contribution from us and would love to [hear from you](mailto:hello@whitecube.be) if you find it useful in your projects. Follow us on [Twitter](https://twitter.com/whitecube_be) for more updates!