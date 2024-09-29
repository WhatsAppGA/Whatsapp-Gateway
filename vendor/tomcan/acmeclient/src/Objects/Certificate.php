<?php

namespace TomCan\AcmeClient\Objects;

use TomCan\AcmeClient\Interfaces\CertificateInterface;

class Certificate implements CertificateInterface
{
    private string $key;
    private string $csr;
    private string $certificate;

    public function __construct(string $key, string $csr, string $certificate)
    {
        $this->key = $key;
        $this->csr = $csr;
        $this->certificate = $certificate;
    }

    public static function create(string $key, string $csr, string $certificate): CertificateInterface
    {
        return new Certificate($key, $csr, $certificate);
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getCsr(): string
    {
        return $this->csr;
    }

    public function getCertificate(): string
    {
        return $this->certificate;
    }
}
