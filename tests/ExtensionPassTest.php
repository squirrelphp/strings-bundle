<?php

namespace Squirrel\StringsBundle\Tests;

use Squirrel\Strings\Attribute\StringFilterExtension;
use Squirrel\Strings\Attribute\StringFilterProcessor;
use Squirrel\Strings\Filter\TrimFilter;
use Squirrel\Strings\StringFilterSelectInterface;
use Squirrel\Strings\Twig\StringExtension;
use Squirrel\StringsBundle\DependencyInjection\Compiler\ExtensionPass;
use Squirrel\StringsBundle\DependencyInjection\Compiler\RandomStringGeneratorPass;
use Squirrel\StringsBundle\DependencyInjection\Compiler\StringFilterPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class ExtensionPassTest extends \PHPUnit\Framework\TestCase
{
    public function testNoFormNoTwig(): void
    {
        $container = new ContainerBuilder();

        $this->processCompilerPass($container);

        // service container + filter selector + 36 default filters + random selector + 4 generators
        // + annotation processor
        $this->assertEquals(44, \count($container->getDefinitions()));

        // Make sure all definitions exist that we expect
        $this->assertTrue($container->hasDefinition(StringFilterSelectInterface::class));
        $this->assertTrue($container->hasDefinition(StringFilterProcessor::class));

        $container->compile();
    }

    public function testFormAndTwig(): void
    {
        $container = new ContainerBuilder();

        $container->setDefinition('form.factory', new Definition(TrimFilter::class));
        $container->setDefinition('twig', new Definition(TrimFilter::class));

        $this->processCompilerPass($container);

        // service container + filter selector + 36 default filters + random selector + 4 generators
        // + annotation processor + form type extension + twig extension + form.factory + twig
        $this->assertEquals(48, \count($container->getDefinitions()));

        // Make sure all definitions exist that we expect
        $this->assertTrue($container->hasDefinition(StringFilterSelectInterface::class));
        $this->assertTrue($container->hasDefinition(StringFilterProcessor::class));
        $this->assertTrue($container->hasDefinition(StringFilterExtension::class));
        $this->assertTrue($container->hasDefinition(StringExtension::class));

        $container->compile();
    }

    protected function processCompilerPass(ContainerBuilder $container): void
    {
        (new StringFilterPass())->process($container);
        (new RandomStringGeneratorPass())->process($container);
        (new ExtensionPass())->process($container);
    }
}
