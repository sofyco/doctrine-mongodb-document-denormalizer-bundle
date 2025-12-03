<?php declare(strict_types=1);

namespace Sofyco\Bundle\Doctrine\MongoDB\DocumentDenormalizerBundle\Serializer;

use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final readonly class DocumentDenormalizer implements DenormalizerInterface
{
    public function __construct(private DocumentManager $dm)
    {
    }

    /**
     * @param class-string $type
     */
    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return $this->dm->getMetadataFactory()->hasMetadataFor($type);
    }

    /**
     * @param class-string $type
     */
    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        $repository = $this->dm->getRepository($type);
        $identifier = (string) \current($this->dm->getMetadataFactory()->getMetadataFor($type)->getIdentifier());

        if (\is_string($data)) {
            if (null === $document = $repository->findOneBy([$identifier => $data])) {
                throw new Exception\DocumentNotFoundHttpException();
            }

            return $document;
        }

        return null;
    }

    #[\Override]
    public function getSupportedTypes(?string $format): array
    {
        return ['object' => true];
    }
}
