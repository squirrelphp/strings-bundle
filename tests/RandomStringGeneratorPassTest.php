<?php

namespace Squirrel\StringsBundle\Tests;

use Squirrel\Strings\Exception\StringException;
use Squirrel\Strings\Random\GeneratorAscii;
use Squirrel\Strings\RandomStringGeneratorSelectInterface;
use Squirrel\StringsBundle\DependencyInjection\Compiler\RandomStringGeneratorPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class RandomStringGeneratorPassTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault(): void
    {
        $container = new ContainerBuilder();

        $this->processCompilerPass($container);

        // service container + selector + 4 default generators
        $this->assertEquals(6, \count($container->getDefinitions()));

        // Make sure all definitions exist that we expect
        $this->assertTrue($container->hasDefinition(RandomStringGeneratorSelectInterface::class));
        $this->assertTrue($container->hasDefinition('squirrel.strings.random.62_characters'));
        $this->assertTrue($container->hasDefinition('squirrel.strings.random.readfriendly_uppercase'));

        $definition = $container->getDefinition(RandomStringGeneratorSelectInterface::class);

        $argument = $definition->getArgument(0);

        $this->assertEquals(4, \count($argument));

        $container->compile();
    }

    public function testNoGeneratorName(): void
    {
        $this->expectException(StringException::class);

        $container = new ContainerBuilder();

        $generator = new Definition(GeneratorAscii::class, ['ABCD']);
        $generator->addTag('squirrel.strings.random', [
            'generator' => '',
        ]);

        $container->setDefinition('randomSomeString', $generator);

        $this->processCompilerPass($container);
    }

    public function testDuplicateGeneratorName(): void
    {
        $this->expectException(StringException::class);

        $container = new ContainerBuilder();

        $generator = new Definition(GeneratorAscii::class, ['ABCD']);
        $generator->addTag('squirrel.strings.random', [
            'generator' => '62Characters',
        ]);

        $container->setDefinition('randomSomeString', $generator);

        $this->processCompilerPass($container);
    }

    protected function processCompilerPass(ContainerBuilder $container): void
    {
        (new RandomStringGeneratorPass())->process($container);
    }
}
