<?php declare(strict_types=1);

namespace Sofyco\Bundle\Doctrine\MongoDB\DocumentDenormalizerBundle\DependencyInjection;

use Sofyco\Bundle\Doctrine\MongoDB\DocumentDenormalizerBundle\Serializer\DocumentDenormalizer;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;

final class DocumentDenormalizerExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $denormalizer = new Definition(DocumentDenormalizer::class);
        $denormalizer->setAutowired(true);
        $denormalizer->setAutoconfigured(true);

        $container->setDefinition(DocumentDenormalizer::class, $denormalizer);
    }
}
