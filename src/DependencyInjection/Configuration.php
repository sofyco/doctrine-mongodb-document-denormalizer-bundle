<?php declare(strict_types=1);

namespace Sofyco\Bundle\Doctrine\MongoDB\DocumentDenormalizerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $builder = new TreeBuilder('doctrine_odm_document_denormalizer');

        /** @var ArrayNodeDefinition $root */
        $root = $builder->getRootNode();

        $options = $root->children();
        $options->scalarNode('translation_resource_not_found')->defaultValue('resource.not_found')->end();

        return $builder;
    }
}
