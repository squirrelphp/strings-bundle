<?php

namespace Squirrel\StringsBundle\DependencyInjection\Compiler;

use Squirrel\Strings\Annotation\StringFilterExtension;
use Squirrel\Strings\Annotation\StringFilterProcessor;
use Squirrel\Strings\RandomStringGeneratorSelectInterface;
use Squirrel\Strings\StringFilterSelectInterface;
use Squirrel\Strings\Twig\StringExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Form\AbstractTypeExtension;
use Twig\Extension\AbstractExtension;

/**
 * Add random string generators to offer random string generation
 */
class ExtensionPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $container->setDefinition(StringFilterProcessor::class, new Definition(StringFilterProcessor::class, [
            new Reference('annotation_reader'),
            new Reference(StringFilterSelectInterface::class),
        ]));

        if ($container->has('form.factory') && \class_exists(AbstractTypeExtension::class)) {
            $formExtension = new Definition(StringFilterExtension::class, [
                new Reference(StringFilterProcessor::class),
            ]);
            $formExtension->addTag('form.type_extension');

            $container->setDefinition(StringFilterExtension::class, $formExtension);
        }

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
