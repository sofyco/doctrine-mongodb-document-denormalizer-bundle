<?php declare(strict_types=1);

namespace Sofyco\Bundle\Doctrine\MongoDB\DocumentDenormalizerBundle\Tests\App\DTO;

use Sofyco\Bundle\Doctrine\MongoDB\DocumentDenormalizerBundle\Tests\App\Document\Product;

final class SingleExample
{
    public int $quantity;

    public Product $product;
}
