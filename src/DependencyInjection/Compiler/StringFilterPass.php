<?php

namespace Squirrel\StringsBundle\DependencyInjection\Compiler;

use Squirrel\Debug\Debug;
use Squirrel\Strings\Exception\StringException;
use Squirrel\Strings\Filter\CamelCaseToSnakeCaseFilter;
use Squirrel\Strings\Filter\DecodeAllHTMLEntitiesFilter;
use Squirrel\Strings\Filter\DecodeBasicHTMLEntitiesFilter;
use Squirrel\Strings\Filter\EncodeBasicHTMLEntitiesFilter;
use Squirrel\Strings\Filter\LimitConsecutiveUnixNewlinesFilter;
use Squirrel\Strings\Filter\LowercaseFilter;
use Squirrel\Strings\Filter\NormalizeLettersToAsciiFilter;
use Squirrel\Strings\Filter\NormalizeNewlinesToUnixStyleFilter;
use Squirrel\Strings\Filter\NormalizeToAlphanumericFilter;
use Squirrel\Strings\Filter\NormalizeToAlphanumericLowercaseFilter;
use Squirrel\Strings\Filter\RemoveEmailsFilter;
use Squirrel\Strings\Filter\RemoveExcessSpacesFilter;
use Squirrel\Strings\Filter\RemoveHTMLTagCharacters;
use Squirrel\Strings\Filter\RemoveHTMLTagsFilter;
use Squirrel\Strings\Filter\RemoveNonAlphanumericFilter;
use Squirrel\Strings\Filter\RemoveNonAsciiAndControlCharactersFilter;
use Squirrel\Strings\Filter\RemoveNonNumericFilter;
use Squirrel\Strings\Filter\RemoveNonUTF8CharactersFilter;
use Squirrel\Strings\Filter\RemoveURLsFilter;
use Squirrel\Strings\Filter\RemoveZeroWidthSpacesFilter;
use Squirrel\Strings\Filter\ReplaceNewlinesWithSpacesFilter;
use Squirrel\Strings\Filter\ReplaceNonAlphanumericFilter;
use Squirrel\Strings\Filter\ReplaceTabsWithSpacesFilter;
use Squirrel\Strings\Filter\ReplaceUnicodeWhitespacesFilter;
use Squirrel\Strings\Filter\ReplaceUnixStyleNewlinesWithParagraphsAndBreaksFilter;
use Squirrel\Strings\Filter\SnakeCaseToCamelCaseFilter;
use Squirrel\Strings\Filter\StreamlineEmailFilter;
use Squirrel\Strings\Filter\StreamlineInputNoNewlinesFilter;
use Squirrel\Strings\Filter\StreamlineInputWithNewlinesFilter;
use Squirrel\Strings\Filter\TrimFilter;
use Squirrel\Strings\Filter\UppercaseFilter;
use Squirrel\Strings\Filter\UppercaseFirstCharacterFilter;
use Squirrel\Strings\Filter\UppercaseWordsFirstCharacterFilter;
use Squirrel\Strings\Filter\WrapLongWordsNoHTMLFilter;
use Squirrel\Strings\Filter\WrapLongWordsWithHTMLFilter;
use Squirrel\Strings\StringFilterSelectInterface;
use Squirrel\Strings\StringFilterSelector;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Add string filters to StringFilterSelector so filters are available via type hint
 */
class StringFilterPass implements CompilerPassInterface
{
    private array $defaultFilters = [
        CamelCaseToSnakeCaseFilter::class,
        DecodeAllHTMLEntitiesFilter::class,
        DecodeBasicHTMLEntitiesFilter::class,
        EncodeBasicHTMLEntitiesFilter::class,
        'LimitConsecutiveUnixNewlinesToTwo' => LimitConsecutiveUnixNewlinesFilter::class,
        LowercaseFilter::class,
        NormalizeLettersToAsciiFilter::class,
        NormalizeNewlinesToUnixStyleFilter::class,
        NormalizeToAlphanumericFilter::class,
        NormalizeToAlphanumericLowercaseFilter::class,
        RemoveExcessSpacesFilter::class,
        RemoveEmailsFilter::class,
        RemoveHTMLTagsFilter::class,
        RemoveHTMLTagCharacters::class,
        RemoveNonAlphanumericFilter::class,
        RemoveNonAsciiAndControlCharactersFilter::class,
        RemoveNonNumericFilter::class,
        RemoveNonUTF8CharactersFilter::class,
        RemoveURLsFilter::class,
        RemoveZeroWidthSpacesFilter::class,
        ReplaceUnixStyleNewlinesWithParagraphsAndBreaksFilter::class,
        ReplaceNewlinesWithSpacesFilter::class,
        'ReplaceNonAlphanumericWithDash' => ReplaceNonAlphanumericFilter::class,
        ReplaceTabsWithSpacesFilter::class,
        ReplaceUnicodeWhitespacesFilter::class,
        SnakeCaseToCamelCaseFilter::class,
        StreamlineEmailFilter::class,
        StreamlineInputNoNewlinesFilter::class,
        StreamlineInputWithNewlinesFilter::class,
        TrimFilter::class,
        UppercaseFilter::class,
        UppercaseFirstCharacterFilter::class,
        UppercaseWordsFirstCharacterFilter::class,
        'WrapLongWordsNoHTML20Chars' => WrapLongWordsNoHTMLFilter::class,
        'WrapLongWordsWithHTML20Chars' => WrapLongWordsWithHTMLFilter::class,
    ];

    public function process(ContainerBuilder $container): void
    {
        // Add all default filters to service container
        foreach ($this->defaultFilters as $name => $class) {
            if (\is_int($name)) {
                $name = \str_replace('Squirrel\\Strings\\Filter\\', '', $class);
                $name = \preg_replace('/Filter$/si', '', $name);
            }

            $definition = new Definition($class);
            $definition->addTag('squirrel.strings.filter', [
                'filter' => $name,
            ]);

            $container->setDefinition($class, $definition);
        }

        // Get all string filter services and create constructor argument for Selector
        $taggedServicesWithNames = $this->createFilterDefinitionList(
            $container->findTaggedServiceIds('squirrel.strings.filter')
        );

        // Add filters to Selector class
        $container->setDefinition(StringFilterSelectInterface::class, new Definition(StringFilterSelector::class, [$taggedServicesWithNames]));
    }

    private function createFilterDefinitionList(array $taggedServices): array
    {
        $taggedServicesWithNames = [];

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                // Filter name not set or zero length, although it is mandatory
                if (!isset($attributes['filter']) || \strlen($attributes['filter']) === 0) {
                    throw Debug::createException(StringException::class, [], 'String filter name not provided for ' . Debug::sanitizeData($attributes['filter']));
                }

                // Filter with the same name already exists
                if (isset($taggedServicesWithNames[$attributes['filter']])) {
                    throw Debug::createException(StringException::class, [], 'String filter name is used twice: ' . Debug::sanitizeData($attributes['filter']) . ' with service ' . $id);
                }

                $taggedServicesWithNames[$attributes['filter']] = new Reference($id);
            }
        }

        return $taggedServicesWithNames;
    }
}
