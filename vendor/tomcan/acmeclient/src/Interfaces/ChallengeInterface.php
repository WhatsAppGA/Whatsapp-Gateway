<?php

namespace TomCan\AcmeClient\Interfaces;

interface ChallengeInterface
{
    public static function create(string $type, string $status, string $url, string $token, string $value): ChallengeInterface;
    public function getType(): string;
    public function getStatus(): string;
    public function setStatus(string $status): void;
    public function getUrl(): string;
    public function getToken(): string;
    public function getValue(): string;
}
