<?php

namespace App\Service;

use DateInterval;
use DateTimeImmutable;
use Firebase\JWT\JWT;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class JWTGenerator
{
    public function __construct(
        #[Autowire('%env(ZOOM_VIDEO_SDK_KEY)%')]
        private string $zoomVideoKey,
        #[Autowire('%env(ZOOM_VIDEO_SDK_SECRET)%')]
        private string $zoomVideoSecret,
        #[Autowire('%env(TOKEN_TTL)%')]
        private string $tokenTTL
    ) {
    }

    public function generate(string $roomIdentity, string $userIdentity, bool $isHost = false): string
    {
        $issuedAt = new DateTimeImmutable();
        $expiry = $issuedAt->add(new DateInterval("PT{$this->tokenTTL}S"));

        $payload = [
            'app_key' => $this->zoomVideoKey,
            'role_type' => $isHost ? 1 : 0,
            'version' => 1,
            'tpc' => $roomIdentity,
            'user_identity' => $userIdentity,
            'iat' => $issuedAt->getTimestamp(),
            'exp' => $expiry->getTimestamp(),
        ];
        return JWT::encode($payload, $this->zoomVideoSecret, 'HS256');
    }
}
