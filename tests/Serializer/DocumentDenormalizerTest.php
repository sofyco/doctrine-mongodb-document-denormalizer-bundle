<?php declare(strict_types=1);

namespace Sofyco\Bundle\Doctrine\MongoDB\DocumentDenormalizerBundle\Tests\Serializer;

use Doctrine\ODM\MongoDB\DocumentManager;
use Sofyco\Bundle\Doctrine\MongoDB\DocumentDenormalizerBundle\Tests\App\Document\Product;
use Sofyco\Bundle\Doctrine\MongoDB\DocumentDenormalizerBundle\Tests\App\DTO\MultipleExample;
use Sofyco\Bundle\Doctrine\MongoDB\DocumentDenormalizerBundle\Tests\App\DTO\SingleExample;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

final class DocumentDenormalizer extends KernelTestCase
{
    public function testDenormalizeSingleDocument(): void
    {
        $this->loadFixtures();

        /** @var Product $product */
        $product = self::getDocumentManager()->getRepository(Product::class)->findOneBy(['name' => 'product_2']);
        $data = ['quantity' => 2, 'product' => $product->id];

        /** @var SingleExample $object */
        $object = self::getSerializer()->denormalize($data, SingleExample::class);

        self::assertSame($object->quantity, $data['quantity']);
        self::assertSame($object->product->id, $product->id);
        self::assertSame($object->product->name, $product->name);
    }

    public function testDocumentNotFound(): void
    {
        self::expectException(NotFoundHttpException::class);
        self::expectExceptionMessage('resource.not_found');

        self::getDocumentManager()->getRepository(Product::class); // bug with metadata

        $data = ['quantity' => 2, 'product' => '123'];

        self::getSerializer()->denormalize($data, SingleExample::class);
    }

    public function testInvalidData(): void
    {
        self::expectException(NotNormalizableValueException::class);

        self::getDocumentManager()->getRepository(Product::class); // bug with metadata

        $data = ['quantity' => 2, 'product' => 123];

        self::getSerializer()->denormalize($data, SingleExample::class);
    }

    public function testDenormalizeMultipleDocuments(): void
    {
        // @todo will be implemented
        $this->loadFixtures();

        /** @var Product[] $products */
        $products = self::getDocumentManager()
            ->createQueryBuilder(Product::class)
            ->field('name')->in([]) // ['product_2', 'product_3']
            ->getQuery()
            ->getIterator()
            ->toArray();

        $data = ['quantity' => 3, 'products' => array_map(fn(Product $product) => $product->id, $products)];

        /** @var MultipleExample $object */
        $object = self::getSerializer()->denormalize($data, MultipleExample::class);

        self::assertSame($object->quantity, $data['quantity']);

        foreach ($products as $product) {
            /** @var Product $actual */
            $actual = \current($object->products);

            self::assertSame($product->id, $actual->id);
            self::assertSame($product->name, $actual->name);

            \next($object->products);
        }
    }

    private function loadFixtures(): void
    {
        self::getDocumentManager()->createQueryBuilder()->remove(Product::class)->getQuery()->execute();

        foreach (\range(1, 3) as $i) {
            self::getDocumentManager()->persist(new Product(\sprintf('product_%d', $i)));
        }

        self::getDocumentManager()->flush();
    }

    private static function getSerializer(): Serializer
    {
        return self::getContainer()->get(SerializerInterface::class); // @phpstan-ignore-line
    }

    private static function getDocumentManager(): DocumentManager
    {
        return self::getContainer()->get(DocumentManager::class); // @phpstan-ignore-line
    }
}
