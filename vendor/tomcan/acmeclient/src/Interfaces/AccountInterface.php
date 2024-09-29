<?php

namespace TomCan\AcmeClient\Interfaces;

interface AccountInterface
{
    public static function create(string $email, string $url, ?string $key): AccountInterface;
    public function getEmail(): string;
    public function getUrl(): ?string;
    public function setUrl(string $url): void;
    public function getKey(): ?string;
}
