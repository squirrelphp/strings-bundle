Squirrel Strings Integration for Symfony
========================================

[![Build Status](https://img.shields.io/travis/com/squirrelphp/strings-bundle.svg)](https://travis-ci.com/squirrelphp/strings-bundle) [![Test Coverage](https://api.codeclimate.com/v1/badges/f6e0f7b91f266787ce0c/test_coverage)](https://codeclimate.com/github/squirrelphp/strings-bundle/test_coverage) ![PHPStan](https://img.shields.io/badge/style-level%208-success.svg?style=flat-round&label=phpstan) [![Packagist Version](https://img.shields.io/packagist/v/squirrelphp/strings-bundle.svg?style=flat-round)](https://packagist.org/packages/squirrelphp/strings-bundle)  [![PHP Version](https://img.shields.io/packagist/php-v/squirrelphp/strings-bundle.svg)](https://packagist.org/packages/squirrelphp/strings-bundle) [![Software License](https://img.shields.io/badge/license-MIT-success.svg?style=flat-round)](LICENSE)

Integration of [squirrelphp/strings](https://github.com/squirrelphp/strings) into Symfony through service tags and bundle configuration.

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

The default ones are (links go to `squirrelphp/strings` documentation):

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
- [Lowercase](https://github.com/squirrelphp/strings#lowercase)
- [Uppercase](https://github.com/squirrelphp/strings#uppercase)
- [UppercaseFirstCharacter](https://github.com/squirrelphp/strings#uppercasefirstcharacter)
- [UppercaseWordsFirstCharacter](https://github.com/squirrelphp/strings#uppercasewordsfirstcharacter)
- [CamelCaseToSnakeCase](https://github.com/squirrelphp/strings#camelcasetosnakecase)
- [SnakeCaseToCamelCase](https://github.com/squirrelphp/strings#snakecasetocamelcase)
- [RemoveHTMLTags](https://github.com/squirrelphp/strings#removehtmltags)
- [ReplaceUnixStyleNewlinesWithParagraphs](https://github.com/squirrelphp/strings#replaceunixstylenewlineswithparagraphs)
- [EncodeBasicHTMLEntities](https://github.com/squirrelphp/strings#encodebasichtmlentities)
- [DecodeBasicHTMLEntities](https://github.com/squirrelphp/strings#decodebasichtmlentities)
- [DecodeAllHTMLEntities](https://github.com/squirrelphp/strings#decodeallhtmlentities)
- [RemoveNonUTF8Characters](https://github.com/squirrelphp/strings#removenonutf8characters)
- [RemoveNonAlphanumeric](https://github.com/squirrelphp/strings#removenonalphanumeric)
- [RemoveNonNumeric](https://github.com/squirrelphp/strings#removenonnumeric)
- [RemoveNonAsciiAndControlCharacters](https://github.com/squirrelphp/strings#removenonasciiandcontrolcharacters)
- [RemoveEmails](https://github.com/squirrelphp/strings#removeemails)
- [RemoveURLs](https://github.com/squirrelphp/strings#removeurls)
- [NormalizeLettersToAscii](https://github.com/squirrelphp/strings#normalizeletterstoascii)
- [NormalizeToAlphanumeric](https://github.com/squirrelphp/strings#normalizetoalphanumeric)
- [NormalizeToAlphanumericLowercase](https://github.com/squirrelphp/strings#normalizetoalphanumericlowercase)
- [ReplaceNonAlphanumericWithDash](https://github.com/squirrelphp/strings#replacenonalphanumeric)
- [StreamlineInputWithNewlines](https://github.com/squirrelphp/strings#streamlineinputwithnewlines)
- [StreamlineInputNoNewlines](https://github.com/squirrelphp/strings#streamlineinputnonewlines)

You can typehint `Squirrel\Strings\StringFilterSelectInterface` to get a service through which all filters are accessible by the getFilter method:

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

The filter will be available in `Squirrel\Strings\StringFilterSelectInterface` under the name `MyCustomStringFilter` (the `filter` value you defined for the tag) as well as in the StringFilter attribute.

### Random string generators

Generates random strings according to a list of possible characters. The following services are configured by default and can be injected into your services (they are implementing `Squirrel\Strings\RandomStringGeneratorInterface`):

- `squirrel.strings.random.62_characters` generates a random string with the 62 alphanumeric characters (A-Z, a-z and 0-9)
- `squirrel.strings.random.36_characters` generates a random string with the 36 alphanumeric lowercase characters (a-z and 0-9)
- `squirrel.strings.readfriendly_uppercase` generates a random string with 27 easily distinguishable uppercase characters (`234579ACDEFGHKMNPQRSTUVWXYZ`), ideal if a human has to view and enter a code with such characters
- `squirrel.strings.readfriendly_lowercase` generates a random string with 27 easily distinguishable lowercase characters (`234579acdefghkmnpqrstuvwxyz`), the same as the above uppercase variant, just in lowercase

You can add your own random string generator by creating a class implementing `Squirrel\Strings\RandomStringGeneratorInterface` and tagging it with `quirrel.strings.random`:

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

### String to number condensing

Todo!