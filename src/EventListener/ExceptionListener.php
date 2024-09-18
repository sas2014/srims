<?php

namespace App\EventListener;

use App\Messages\AppMessageConstants;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Exception\PartialDenormalizationException;
use Symfony\Component\Serializer\Normalizer\ProblemNormalizer;

class ExceptionListener implements EventSubscriberInterface
{

    /**
     * @var bool
     */
    private bool $debug;

    /**
     * @param bool $debug
     */
    public function __construct(bool $debug)
    {
        $this->debug = $debug;
    }

    /**
     * @return array[]
     */
    public static function getSubscribedEvents(): array
    {
        $priority = 0;
        return array(
            KernelEvents::EXCEPTION => [
                ['onKernelException', $priority],
            ]
        );
    }

    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        switch (true) {
            case $exception instanceof NotFoundHttpException:
                $event->setResponse(
                    new JsonResponse(
                        ['message' => AppMessageConstants::RESOURCE_NOT_FOUND],
                        JsonResponse::HTTP_NOT_FOUND
                    )
                );
                break;
            default:
        }
    }

}
