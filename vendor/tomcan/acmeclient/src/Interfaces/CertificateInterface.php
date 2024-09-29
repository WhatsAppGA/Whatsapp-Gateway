<?php

namespace TomCan\AcmeClient\Interfaces;

interface CertificateInterface
{
    public static function create(string $key, string $csr, string $certificate): CertificateInterface;
    public function getKey(): string;
    public function getCsr(): string;
    public function getCertificate(): string;
}
