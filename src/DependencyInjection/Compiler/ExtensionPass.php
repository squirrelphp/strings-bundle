<?php

namespace Squirrel\StringsBundle\DependencyInjection\Compiler;

use Squirrel\Strings\Attribute\StringFilterExtension;
use Squirrel\Strings\Attribute\StringFilterProcessor;
use Squirrel\Strings\RandomStringGeneratorSelectInterface;
use Squirrel\Strings\StringFilterSelectInterface;
use Squirrel\Strings\Twig\StringExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Form\AbstractTypeExtension;
use Twig\Extension\AbstractExtension;

class ExtensionPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $container->setDefinition(StringFilterProcessor::class, new Definition(StringFilterProcessor::class, [
            new Reference(StringFilterSelectInterface::class),
        ]));

        // Add string filter attribute support for form data classes
        if ($container->has('form.factory') && \class_exists(AbstractTypeExtension::class)) {
            $formExtension = new Definition(StringFilterExtension::class, [
                new Reference(StringFilterProcessor::class),
            ]);
            $formExtension->addTag('form.type_extension');

            $container->setDefinition(StringFilterExtension::class, $formExtension);
        }

        // Add access to string filters and random string generators in twig
        if ($container->has('twig') && \class_exists(AbstractExtension::class)) {
            $twigExtension = new Definition(StringExtension::class, [
                new Reference(StringFilterSelectInterface::class),
                new Reference(RandomStringGeneratorSelectInterface::class),
            ]);
            $twigExtension->addTag('twig.extension');

            $container->setDefinition(StringExtension::class, $twigExtension);
        }
    }
}
