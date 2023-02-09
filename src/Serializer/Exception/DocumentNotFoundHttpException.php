<?php declare(strict_types=1);

namespace Sofyco\Bundle\Doctrine\MongoDB\DocumentDenormalizerBundle\Serializer\Exception;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class DocumentNotFoundHttpException extends NotFoundHttpException
{
    public function __construct()
    {
        parent::__construct('resource.notFound');
    }
}
