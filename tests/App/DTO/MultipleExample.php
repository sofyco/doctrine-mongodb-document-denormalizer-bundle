<?php declare(strict_types=1);

namespace Sofyco\Bundle\Doctrine\MongoDB\DocumentDenormalizerBundle\Tests\App\DTO;

use Sofyco\Bundle\Doctrine\MongoDB\DocumentDenormalizerBundle\Tests\App\Document\Product;

final class MultipleExample
{
    public int $quantity;

    /**
     * @var iterable|Product[]
     */
    public iterable $products = [];
}
