Squirrel Strings Integration for Symfony
========================================

[![Build Status](https://img.shields.io/travis/com/squirrelphp/strings-bundle.svg)](https://travis-ci.com/squirrelphp/strings-bundle) [![Test Coverage](https://api.codeclimate.com/v1/badges/f6e0f7b91f266787ce0c/test_coverage)](https://codeclimate.com/github/squirrelphp/strings-bundle/test_coverage) ![PHPStan](https://img.shields.io/badge/style-level%208-success.svg?style=flat-round&label=phpstan) [![Packagist Version](https://img.shields.io/packagist/v/squirrelphp/strings-bundle.svg?style=flat-round)](https://packagist.org/packages/squirrelphp/strings-bundle)  [![PHP Version](https://img.shields.io/packagist/php-v/squirrelphp/strings-bundle.svg)](https://packagist.org/packages/squirrelphp/strings-bundle) [![Software License](https://img.shields.io/badge/license-MIT-success.svg?style=flat-round)](LICENSE)

Integration of [squirrelphp/strings](https://github.com/squirrelphp/strings) into a Symfony project through service tags and bundle configuration.

Installation
------------

```
composer require squirrelphp/strings-bundle
```

Configuration
-------------

Enable the bundle by adding `Squirrel\StringsBundle\SquirrelStringsBundle` to the list of your used bundles. The bundle then configures itself automatically.

Usage
-----

### String filters

The default filters are grouped in six categories (links go to `squirrelphp/strings` documentation):

#### [Newlines, tabs and spaces](https://github.com/squirrelphp/strings#newlines-tabs-and-spaces)

- [NormalizeNewlinesToUnixStyle](https://github.com/squirrelphp/strings#normalizenewlinestounixstyle)
- [ReplaceUnicodeWhitespaces](https://github.com/squirrelphp/strings#replaceunicodewhitespaces)
- [RemoveExcessSpaces](https://github.com/squirrelphp/strings#removeexcessspaces)
- [LimitConsecutiveUnixNewlinesToTwo](https://github.com/squirrelphp/strings#limitconsecutiveunixnewlines)
- [RemoveZeroWidthSpaces](https://github.com/squirrelphp/strings#removezerowidthspaces)
- [ReplaceNewlinesWithSpaces](https://github.com/squirrelphp/strings#replacenewlineswithspaces)
- [Trim](https://github.com/squirrelphp/strings#trim)
- [ReplaceTabsWithSpaces](https://github.com/squirrelphp/strings#replacetabswithspaces)
- [WrapLongWordsNoHTML20Chars](https://github.com/squirrelphp/strings#wraplongwordsnohtml)
- [WrapLongWordsWithHTML20Chars](https://github.com/squirrelphp/strings#wraplongwordswithhtml)

#### [Cases: lowercase, uppercase, camelcase, snakecase](https://github.com/squirrelphp/strings#cases-lowercase-uppercase-camelcase-snakecase)

- [Lowercase](https://github.com/squirrelphp/strings#lowercase)
- [Uppercase](https://github.com/squirrelphp/strings#uppercase)
- [UppercaseFirstCharacter](https://github.com/squirrelphp/strings#uppercasefirstcharacter)
- [UppercaseWordsFirstCharacter](https://github.com/squirrelphp/strings#uppercasewordsfirstcharacter)
- [CamelCaseToSnakeCase](https://github.com/squirrelphp/strings#camelcasetosnakecase)
- [SnakeCaseToCamelCase](https://github.com/squirrelphp/strings#snakecasetocamelcase)

#### [HTML](https://github.com/squirrelphp/strings#html)

- [RemoveHTMLTags](https://github.com/squirrelphp/strings#removehtmltags)
- [RemoveHTMLTagCharacters](https://github.com/squirrelphp/strings#removehtmltagcharacters)
- [ReplaceUnixStyleNewlinesWithParagraphs](https://github.com/squirrelphp/strings#replaceunixstylenewlineswithparagraphs)
- [EncodeBasicHTMLEntities](https://github.com/squirrelphp/strings#encodebasichtmlentities)
- [DecodeBasicHTMLEntities](https://github.com/squirrelphp/strings#decodebasichtmlentities)
- [DecodeAllHTMLEntities](https://github.com/squirrelphp/strings#decodeallhtmlentities)

#### [Remove/restrict characters and content](https://github.com/squirrelphp/strings#remove-restrict-characters-and-content)

- [RemoveNonUTF8Characters](https://github.com/squirrelphp/strings#removenonutf8characters)
- [RemoveNonAlphanumeric](https://github.com/squirrelphp/strings#removenonalphanumeric)
- [RemoveNonAlphabetic](https://github.com/squirrelphp/strings#removenonalphabetic)
- [RemoveNonNumeric](https://github.com/squirrelphp/strings#removenonnumeric)
- [RemoveNonAsciiAndControlCharacters](https://github.com/squirrelphp/strings#removenonasciiandcontrolcharacters)
- [RemoveEmails](https://github.com/squirrelphp/strings#removeemails)
- [RemoveURLs](https://github.com/squirrelphp/strings#removeurls)

#### [Normalize to ASCII](https://github.com/squirrelphp/strings#normalize-to-ascii)

- [NormalizeLettersToAscii](https://github.com/squirrelphp/strings#normalizeletterstoascii)
- [NormalizeToAlphanumeric](https://github.com/squirrelphp/strings#normalizetoalphanumeric)
- [NormalizeToAlphanumericLowercase](https://github.com/squirrelphp/strings#normalizetoalphanumericlowercase)
- [ReplaceNonAlphanumericWithDash](https://github.com/squirrelphp/strings#replacenonalphanumeric)

#### [Streamline input](https://github.com/squirrelphp/strings#streamline-input)

- [StreamlineInputWithNewlines](https://github.com/squirrelphp/strings#streamlineinputwithnewlines)
- [StreamlineInputNoNewlines](https://github.com/squirrelphp/strings#streamlineinputnonewlines)

You can typehint `Squirrel\Strings\StringFilterSelectInterface` to get a service where all filters are accessible via the getFilter method:

```php
function (\Squirrel\Strings\StringFilterSelectInterface $selector) {
    $string = "hello\n\nthanks a lot!\nbye";

    $string = $selector->getFilter('ReplaceNewlinesWithSpaces')
        ->filter($string);

    // Outputs "hello  thanks a lot! bye"
    echo $string;
}
```

You can also directly typehint a filter class, like `Squirrel\Strings\Filter\NormalizeToAlphanumeric` - all classes are registered as services in Symfony with their class names. All filter classes can also be instantiated in your application.

#### Form string filtering

This bundle automatically configures string filters for your form values that you can use via attributes, example:

```php
<?php

use Squirrel\Strings\Annotation\StringFilter;

class NewsletterChangeAction
{
    #[StringFilter("StreamlineInputNoNewlines","RemoveHTMLTags")]
    public string $firstName = '';

    #[StringFilter("RemoveNonAlphanumeric")]
    public string $confirmToken = '';
}
```

You can run one or more string filters and use any of the default list of filters or any of [your own filters which you added](#adding-new-filters). The filters are run as an early PRE_SUBMIT form event.

Beware: It only works if you map your class properties (`$firstName` and `$lastName` in this example) to the form without custom property paths, so the names used in the form have to be identical to the property names in the class. As long as you do not specify `property_path` in your form you are safe.

#### Adding new filters

Create a class, implement `Squirrel\Strings\StringFilterInterface` and tag the service with `squirrel.strings.filter` in a Symfony config file like this:

```yaml
services:
    MyCustomStringFilter:
        tags:
            - { name: squirrel.strings.filter, filter: MyCustomStringFilter }
```

The filter will be available in `Squirrel\Strings\StringFilterSelectInterface` under the name `MyCustomStringFilter` (the `filter` value you defined for the tag) as well as in the `StringFilter` attribute.

### Random string generators

Generates random strings according to a list of possible characters. The following services are configured by default and can be injected into your services (they are implementing `Squirrel\Strings\RandomStringGeneratorInterface`):

- `squirrel.strings.random.62_characters` generates a random string with the 62 alphanumeric characters (A-Z, a-z and 0-9)
- `squirrel.strings.random.36_characters` generates a random string with the 36 alphanumeric lowercase characters (a-z and 0-9)
- `squirrel.strings.readfriendly_uppercase` generates a random string with 27 easily distinguishable uppercase characters (`234579ACDEFGHKMNPQRSTUVWXYZ`), ideal if a human has to view and enter a code with such characters
- `squirrel.strings.readfriendly_lowercase` generates a random string with 27 easily distinguishable lowercase characters (`234579acdefghkmnpqrstuvwxyz`), the same as the above uppercase variant, just in lowercase

You can add your own random string generator by creating a class implementing `Squirrel\Strings\RandomStringGeneratorInterface` and tagging it with `squirrel.strings.random`:

```yaml
services:
    MyCustomRandomGenerator:
        tags:
            - { name: squirrel.strings.random, generator: MyGenerator }
```

The generator name in camel case is used when getting a generator via the `getGenerator` method from the service `Squirrel\Strings\RandomStringGeneratorSelectInterface` (so `62Characters` instead of `62_characters`, and `ReadfriendlyLowercase` instead of `readfriendly_lowercase`), or if you want to inject the random string generator directly just convert the generator name to snake case, so in this example you could inject the service with `@squirrel.strings.random.my_generator`.

The classes `Squirrel\Strings\Random\GeneratorAscii` and `Squirrel\Strings\Random\GeneratorUnicode` are good base classes to use for your own needs, where you only need to define the allowed characters in the constructor:

```yaml
services:
    MyCustomRandomGenerator:
        class: Squirrel\Strings\Random\GeneratorAscii
        arguments:
            - '6789'
        tags:
            - { name: squirrel.strings.random, generator: MyGenerator }

    MyOtherCustomRandomGenerator:
        class: Squirrel\Strings\Random\GeneratorUnicode
        arguments:
            - 'öéèä'
        tags:
            - { name: squirrel.strings.random, generator: MyUnicodeGenerator }
```

The first one would create a generator where only the characters 6, 7, 8 and 9 are generated, the second one where only the unicode characters ö, é, è and ä are generated. Just make sure to not use a character twice, otherwise the class will throw an exception.

### Condense a string into a number

Convert an integer to a string with a given "character set" - this way we can encode an integer to condense it (so an integer with 8 numbers is now only a 4-character-string) and later convert it back when needed.

More information is available in the `squirrelphp/strings` library, this bundle does not provide any additional service definitions for condensing at this point.

### URL

The URL class accepts an URL in the constructor and then lets you get or change certain parts of the URL to do the following:

- Get scheme, host, path, query string and specific query string variables
- Change an absolute URL to a relative URL
- Change scheme, host, path and query string
- Replace query string variables, or add/remove them

This can be used to easily build or change your URLs, or to sanitize certain parts of a given URL, for example when redirecting: use the relative URL instead of the absolute URL to avoid malicious redirecting to somewhere outside of your control.

This functionality is taken from `squirrelphp/strings`, this bundle does not provide any additional functionality to handle URLs.

### Regex wrapper

Using the built-in `preg_match`, `preg_match_all`, `preg_replace` and `preg_replace_callback` PHP functions often makes code less readable and harder to understand for static analyzers because of its uses of references (`$matches`) and the many possible return values. `Squirrel\Strings\Regex` wraps the basic functionality of these preg functions, creates easier to understand return values and throws a `Squirrel\Strings\Exception\RegexException` if anything goes wrong. These are the available static methods for the Regex class:

(This functionality is taken from `squirrelphp/strings`, this bundle does not provide any additional functionality to handle Regex)

#### `Regex::isMatch(string $pattern, string $subject, int $offset): bool`

Wraps `preg_match` to check if `$pattern` exists in `$subject`.

#### `Regex::getMatches(string $pattern, string $subject, int $flags, int $offset): ?array`

Wraps `preg_match_all` to retrieve all occurences of `$pattern` in `$subject` with `PREG_UNMATCHED_AS_NULL` flag always set and the possibility to add additional flags. Returns null if no matches are found, otherwise the array of results as set by `preg_match_all` for `$matches`.

#### `Regex::replace(string|array $pattern, string|array $replacement, string $subject, int $limit): string`

Wraps `preg_replace` to replace occurences of `$pattern` with `$replacement` and only accepts a string as `$subject`.

#### `Regex::replaceArray(string|array $pattern, string|array $replacement, array $subject, int $limit): array`

Wraps `preg_replace` to replace occurences of `$pattern` with `$replacement` and only accepts an array as `$subject`.

#### `Regex::replaceWithCallback(string|array $pattern, callback $callback, string $subject, int $limit, int $flags): string`

Wraps `preg_replace_callback` to call a callback with the signature `function(array $matches): string` for each occurence of `$pattern` in `$subject` and only accepts a string as `$subject`.

#### `Regex::replaceArrayWithCallback(string|array $pattern, callback $callback, array $subject, int $limit, int $flags): array`

Wraps `preg_replace_callback` to call a callback with the signature `function(array $matches): string` for each occurence of `$pattern` in `$subject` and only accepts an array as `$subject`.