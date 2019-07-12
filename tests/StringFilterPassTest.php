<?php

namespace Squirrel\StringsBundle\Tests;

use Squirrel\Strings\Exception\StringException;
use Squirrel\Strings\Filter\LimitConsecutiveUnixNewlinesFilter;
use Squirrel\Strings\Filter\RemoveNonUTF8CharactersFilter;
use Squirrel\Strings\Filter\TrimFilter;
use Squirrel\Strings\StringFilterSelectInterface;
use Squirrel\StringsBundle\DependencyInjection\Compiler\StringFilterPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class StringFilterPassTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault()
    {
        $container = new ContainerBuilder();

        $this->processCompilerPass($container);

        // service container + selector + 33 default filters
        $this->assertEquals(35, \count($container->getDefinitions()));

        // Make sure all definitions exist that we expect
        $this->assertTrue($container->hasDefinition(StringFilterSelectInterface::class));
        $this->assertTrue($container->hasDefinition(LimitConsecutiveUnixNewlinesFilter::class));
        $this->assertTrue($container->hasDefinition(RemoveNonUTF8CharactersFilter::class));

        $definition = $container->getDefinition(StringFilterSelectInterface::class);

        $argument = $definition->getArgument(0);

        $this->assertEquals(33, \count($argument));

        $container->compile();
    }

    public function testNoFilterName()
    {
        $this->expectException(StringException::class);

        $container = new ContainerBuilder();

        // Add filter with no filter name
        $trimFilter = new Definition(TrimFilter::class);
        $trimFilter->addTag('squirrel.strings.filter', [
            'filter' => '',
        ]);

        $container->setDefinition('trimmyFilter', $trimFilter);

        $this->processCompilerPass($container);
    }

    public function testDuplicateFilterName()
    {
        $this->expectException(StringException::class);

        $container = new ContainerBuilder();

        // Add filter with the same name as a default filter ("Trim")
        $trimFilter = new Definition(TrimFilter::class);
        $trimFilter->addTag('squirrel.strings.filter', [
            'filter' => 'Trim',
        ]);

        $container->setDefinition('trimmyFilter', $trimFilter);

        $this->processCompilerPass($container);
    }

    protected function processCompilerPass(ContainerBuilder $container)
    {
        (new StringFilterPass())->process($container);
    }
}
