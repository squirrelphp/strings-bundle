<?php

namespace Squirrel\StringsBundle\DependencyInjection\Compiler;

use Squirrel\Debug\Debug;
use Squirrel\Strings\Exception\StringException;
use Squirrel\Strings\Filter\CamelCaseToSnakeCaseFilter;
use Squirrel\Strings\Random\GeneratorAscii;
use Squirrel\Strings\RandomStringGeneratorSelectInterface;
use Squirrel\Strings\RandomStringGeneratorSelector;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Add random string generators to offer random string generation
 */
class RandomStringGeneratorPass implements CompilerPassInterface
{
    /**
     * @var array<string, string> $defaultGenerators
     */
    private array $defaultGenerators = [
        '62Characters' => "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789",
        '36Characters' => "abcdefghijklmnopqrstuvwxyz0123456789",
        'ReadfriendlyUppercase' => "234579ACDEFGHKMNPQRSTUVWXYZ",
        'ReadfriendlyLowercase' => "234579acdefghkmnpqrstuvwxyz",
    ];

    public function process(ContainerBuilder $container): void
    {
        foreach ($this->defaultGenerators as $name => $range) {
            $definition = new Definition(GeneratorAscii::class, [$range]);
            $definition->addTag('squirrel.strings.random', [
                'generator' => $name,
            ]);

            $container->setDefinition('squirrel.strings.random.' . (new CamelCaseToSnakeCaseFilter())->filter($name), $definition);
        }

        // Get all random string generator services and create constructor argument for Selector
        $taggedServicesWithNames = $this->createGeneratorDefinitionList(
            $container->findTaggedServiceIds('squirrel.strings.random'),
        );

        // Add generators to Selector class
        $container->setDefinition(RandomStringGeneratorSelectInterface::class, new Definition(RandomStringGeneratorSelector::class, [$taggedServicesWithNames]));
    }

    private function createGeneratorDefinitionList(array $taggedServices): array
    {
        $taggedServicesWithNames = [];

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                // Generator name not set or zero length, although it is mandatory
                if (!isset($attributes['generator']) || \strlen($attributes['generator']) === 0) {
                    throw Debug::createException(StringException::class, [], 'Random string generator name not provided for ' . Debug::sanitizeData($attributes['generator']));
                }

                // Generator with the same name already exists
                if (isset($taggedServicesWithNames[$attributes['generator']])) {
                    throw Debug::createException(StringException::class, [], 'Random string generator name is used twice: ' . Debug::sanitizeData($attributes['generator']) . ' with service ' . $id);
                }

                $taggedServicesWithNames[$attributes['generator']] = new Reference($id);
            }
        }

        return $taggedServicesWithNames;
    }
}
