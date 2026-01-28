<?php

namespace App\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class JwtService
{
    public function __construct(
        private string $jwtSecret
    ) {}

    public function createToken(int $sessionId): string
    {
        return JWT::encode([
            'sid' => $sessionId,
            'iat' => time(),
            'exp' => time() + 60 * 60 * 24 * 30,
        ], $this->jwtSecret, 'HS256');
    }

    public function getSessionId(string $token): int
    {
        $payload = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));

        if (!isset($payload->sid)) {
            throw new UnauthorizedHttpException('Bearer', 'Invalid token payload');
        }

        return (int)$payload->sid;
    }
}
