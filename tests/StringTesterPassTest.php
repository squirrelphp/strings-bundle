<?php

namespace Squirrel\StringsBundle\Tests;

use Squirrel\Strings\Tester\ValidDateTimeTester;
use Squirrel\Strings\Tester\ValidUTF8Tester;
use Squirrel\StringsBundle\DependencyInjection\Compiler\StringTesterPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class StringTesterPassTest extends \PHPUnit\Framework\TestCase
{
    public function testDefault()
    {
        $container = new ContainerBuilder();

        $this->processCompilerPass($container);

        // service container + 2 default testers
        $this->assertEquals(3, \count($container->getDefinitions()));

        // Make sure all definitions exist that we expect
        $this->assertTrue($container->hasDefinition(ValidUTF8Tester::class));
        $this->assertTrue($container->hasDefinition(ValidDateTimeTester::class));

        $container->compile();
    }

    protected function processCompilerPass(ContainerBuilder $container)
    {
        (new StringTesterPass())->process($container);
    }
}
