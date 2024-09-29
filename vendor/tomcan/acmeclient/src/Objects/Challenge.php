<?php

namespace TomCan\AcmeClient\Objects;

use TomCan\AcmeClient\Interfaces\ChallengeInterface;

class Challenge implements ChallengeInterface
{
    private string $type;
    private string $status;
    private string $url;
    private string $token;
    private string $value;

    public function __construct(string $type, string $status, string $url, string $token, string $value)
    {
        $this->type = $type;
        $this->status = $status;
        $this->url = $url;
        $this->token = $token;
        $this->value = $value;
    }

    public static function create(string $type, string $status, string $url, string $token, string $value): ChallengeInterface
    {
        return new Challenge($type, $status, $url, $token, $value);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
