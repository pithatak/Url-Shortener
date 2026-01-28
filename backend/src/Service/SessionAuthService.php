<?php

namespace App\Service;

use App\Entity\Session;
use App\Repository\SessionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

final class SessionAuthService
{
    public function __construct(
        private JwtService $jwt,
        private SessionRepository $sessions
    ) {}

    public function getSessionFromRequest(Request $request): Session
    {
        $token = $this->getBearerToken($request);
        $sessionId = $this->jwt->getSessionId($token);

        $session = $this->sessions->find($sessionId);
        if (!$session) {
            throw new UnauthorizedHttpException('Bearer', 'Session not found');
        }

        return $session;
    }

    private function getBearerToken(Request $request): string
    {
        $header = $request->headers->get('Authorization', '');

        if (!str_starts_with($header, 'Bearer ')) {
            throw new UnauthorizedHttpException('Bearer', 'Missing Bearer token');
        }

        return substr($header, 7);
    }
}
