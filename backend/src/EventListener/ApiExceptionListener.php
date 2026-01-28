<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ApiExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();

        if ($e instanceof HttpExceptionInterface) {
            $event->setResponse(
                new JsonResponse(
                    ['error' => $e->getMessage()],
                    $e->getStatusCode()
                )
            );
            return;
        }

        $event->setResponse(
            new JsonResponse(['error' => 'Internal Server Error'], 500)
        );
    }

}
