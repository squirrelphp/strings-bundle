<?php

namespace Squirrel\StringsBundle;

use Squirrel\StringsBundle\DependencyInjection\Compiler\ExtensionPass;
use Squirrel\StringsBundle\DependencyInjection\Compiler\RandomStringGeneratorPass;
use Squirrel\StringsBundle\DependencyInjection\Compiler\StringFilterPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @codeCoverageIgnore Just adds compiler passes to Symfony, there is nothing to test
 */
class SquirrelStringsBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        // Set priorities in order and make it above zero, so our compiler passes are run before the ones
        // from Symfony (form component and twig component) are run
        $container->addCompilerPass(new StringFilterPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 50);
        $container->addCompilerPass(new RandomStringGeneratorPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 49);
        $container->addCompilerPass(new ExtensionPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 48);
    }
}
