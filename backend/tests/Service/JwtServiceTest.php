<?php

namespace App\Tests\Service;

use App\Service\JwtService;
use DomainException;
use PHPUnit\Framework\TestCase;

class JwtServiceTest extends TestCase
{
    private string $secret = 'super_long_secret_key_12345678901234567890';

    public function testCreateTokenReturnsString(): void
    {
        $service = new JwtService($this->secret);

        $token = $service->createToken(123);

        $this->assertIsString($token, 'JWT token should be a string');
        $this->assertMatchesRegularExpression('/^[A-Za-z0-9\-\_=]+\.[A-Za-z0-9\-\_=]+\.[A-Za-z0-9\-\_=]+$/', $token, 'JWT format should match');
    }

    public function testGetSessionIdReturnsCorrectId(): void
    {
        $sessionId = 123;
        $service = new JwtService($this->secret);

        $token = $service->createToken($sessionId);

        $decodedId = $service->getSessionId($token);

        $this->assertSame($sessionId, $decodedId, 'Decoded session ID should match the original');
    }

    public function testGetSessionIdThrowsExceptionOnInvalidToken(): void
    {
        $this->expectException(DomainException::class);
        $service = new JwtService($this->secret);

        $invalidToken = 'invalid.token.value';
        $service->getSessionId($invalidToken);
    }
}
