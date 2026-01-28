<?php

namespace App\Controller\Api;

use App\Service\JwtService;
use App\Service\SessionAuthService;
use App\Service\SessionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class SessionController extends AbstractController
{
    #[Route('/api/session', methods: ['POST'])]
    public function create(
        SessionService $sessionService,
        JwtService     $jwt
    ): JsonResponse
    {
        $session = $sessionService->create();

        return $this->json([
            'token' => $jwt->createToken($session->getId()),
        ]);
    }

    #[Route('/api/session', methods: ['GET'])]
    public function get(SessionAuthService $sessionAuthService, Request $request): JsonResponse
    {
        $session = $sessionAuthService->getSessionFromRequest($request);

        return $this->json([
            'id' => (string)$session->getId(),
            'createdAt' => $session->getCreatedAt(),
        ]);
    }
}
