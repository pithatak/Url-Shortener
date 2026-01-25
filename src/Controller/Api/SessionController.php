<?php

namespace App\Controller\Api;

use App\Entity\Session;
use App\Service\SessionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class SessionController extends AbstractController
{
    #[Route('/api/session', methods: ['POST'])]
    public function create(SessionService $sessionService): JsonResponse
    {
        $session = $sessionService->create();

        $response = $this->json([
            'sessionId' => (string)$session->getId(),
        ]);

        $response->headers->setCookie(
            new Cookie(
                'SESSION_ID',
                (string)$session->getId(),
                strtotime('+30 days'),
                '/',
                null,
                false,
                true
            )
        );

        return $response;
    }

    #[Route('/api/session', methods: ['GET'])]
    public function get(Session $session): JsonResponse
    {
        return $this->json([
            'id' => (string)$session->getId(),
            'createdAt' => $session->getCreatedAt(),
        ]);
    }
}
