<?php

namespace TomCan\AcmeClient\Objects;

use TomCan\AcmeClient\Interfaces\AuthorizationInterface;
use TomCan\AcmeClient\Interfaces\ChallengeInterface;

class Authorization implements AuthorizationInterface
{
    private string $url;
    private string $identifier;
    private string $status;
    private \DateTime $expires;
    /**
     * @var ChallengeInterface[]
     */
    private array $challenges;

    /**
     * @param ChallengeInterface[] $challenges
     */
    public function __construct(string $url, string $identifier, string $status, \DateTime $expires, array $challenges)
    {
        $this->url = $url;
        $this->identifier = $identifier;
        $this->status = $status;
        $this->expires = $expires;
        $this->challenges = $challenges;
    }

    /**
     * @param ChallengeInterface[] $challenges
     */
    public static function create(string $url, string $identifier, string $status, \DateTime $expires, array $challenges): AuthorizationInterface
    {
        return new Authorization($url, $identifier, $status, $expires, $challenges);
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getExpires(): \DateTime
    {
        return $this->expires;
    }

    /**
     * @return ChallengeInterface[]
     */
    public function getChallenges(): array
    {
        return $this->challenges;
    }
}
