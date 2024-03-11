<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Exception\InvalidJsonRequestException;
use App\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if ($exception instanceof InvalidJsonRequestException || $exception instanceof NotFoundException) {
            $response = new JsonResponse(
                data: ['errors' => $exception->getErrors()],
                status: 400,
            );
            $event->setResponse($response);
        }
    }
}