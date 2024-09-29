<?php

namespace TomCan\AcmeClient\Interfaces;

interface AuthorizationInterface
{
    /**
     * @param ChallengeInterface[] $challenges
     */
    public static function create(string $url, string $identifier, string $status, \DateTime $expires, array $challenges): AuthorizationInterface;
    public function getUrl(): string;
    public function getIdentifier(): string;
    public function getStatus(): string;
    public function setStatus(string $status): void;
    public function getExpires(): \DateTime;

    /**
     * @return ChallengeInterface[]
     */
    public function getChallenges(): array;
}
