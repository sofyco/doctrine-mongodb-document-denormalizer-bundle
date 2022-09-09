<?php declare(strict_types=1);

namespace Sofyco\Bundle\Doctrine\MongoDB\DocumentDenormalizerBundle\Serializer;

use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class DocumentDenormalizer implements DenormalizerInterface
{
    public function __construct(private readonly DocumentManager $dm, private readonly string $resourceNotFound)
    {
    }

    /**
     * @param mixed        $data
     * @param class-string $type
     * @param string|null  $format
     *
     * @return bool
     */
    public function supportsDenormalization(mixed $data, string $type, string $format = null): bool
    {
        return $this->dm->getMetadataFactory()->hasMetadataFor($type);
    }

    /**
     * @param mixed        $data
     * @param class-string $type
     * @param string|null  $format
     * @param array        $context
     *
     * @return mixed
     *
     * @throws \Doctrine\Persistence\Mapping\MappingException
     * @throws \ReflectionException
     */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        $repository = $this->dm->getRepository($type);

        if (\is_string($data)) {
            $identifier = (string) \current($this->dm->getMetadataFactory()->getMetadataFor($type)->getIdentifier());

            if (null === $document = $repository->findOneBy([$identifier => $data])) {
                throw new NotFoundHttpException($this->resourceNotFound);
            }

            return $document;
        }

        return null;
    }
}
