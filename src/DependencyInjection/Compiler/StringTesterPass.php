<?php

namespace Squirrel\StringsBundle\DependencyInjection\Compiler;

use Squirrel\Strings\Tester\ValidDateTimeTester;
use Squirrel\Strings\Tester\ValidUTF8Tester;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Add string testers to service container
 */
class StringTesterPass implements CompilerPassInterface
{
    /**
     * @var array
     */
    private $defaultTesters = [
        ValidUTF8Tester::class,
        ValidDateTimeTester::class,
    ];

    public function process(ContainerBuilder $container): void
    {
        // Add all default filters to service container
        foreach ($this->defaultTesters as $name => $class) {
            if (\is_int($name)) {
                $name = \str_replace('Squirrel\\Strings\\Tester\\', '', $class);
                $name = \preg_replace('/Tester$/si', '', $name);
            }

            $definition = new Definition($class);
            $definition->addTag('squirrel.strings.tester', [
                'tester' => $name,
            ]);

            $container->setDefinition($class, $definition);
        }
    }
}
