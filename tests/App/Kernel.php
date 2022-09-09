<?php declare(strict_types=1);

namespace Sofyco\Bundle\Doctrine\MongoDB\DocumentDenormalizerBundle\Tests\App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

final class Kernel extends \Symfony\Component\HttpKernel\Kernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        yield new \Symfony\Bundle\FrameworkBundle\FrameworkBundle();
        yield new \Doctrine\Bundle\MongoDBBundle\DoctrineMongoDBBundle();
        yield new \Sofyco\Bundle\Doctrine\MongoDB\DocumentDenormalizerBundle\DocumentDenormalizerBundle();
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import('config/config.yaml');
    }
}
