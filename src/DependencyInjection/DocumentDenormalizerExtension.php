<?php declare(strict_types=1);

namespace Sofyco\Bundle\Doctrine\MongoDB\DocumentDenormalizerBundle\DependencyInjection;

use Doctrine\ODM\MongoDB\DocumentManager;
use Sofyco\Bundle\Doctrine\MongoDB\DocumentDenormalizerBundle\Serializer\DocumentDenormalizer;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

final class DocumentDenormalizerExtension extends ConfigurableExtension
{
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $denormalizer = new Definition(DocumentDenormalizer::class, [
            new Reference(DocumentManager::class),
            $mergedConfig['translation_resource_not_found'],
        ]);
        $denormalizer->setAutoconfigured(true);

        $container->setDefinition(DocumentDenormalizer::class, $denormalizer);
    }
}
