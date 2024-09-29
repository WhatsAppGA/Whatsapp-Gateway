<?php

namespace TomCan\AcmeClient\Objects;

use TomCan\AcmeClient\Interfaces\AccountInterface;

class Account implements AccountInterface
{
    private string $email;
    private ?string $url;
    private ?string $key;

    public function __construct(string $email, ?string $url, ?string $key)
    {
        $this->email = $email;
        $this->url = $url;
        $this->key = $key;
    }

    public static function create(string $email, ?string $url, ?string $key): AccountInterface
    {
        return new Account($email, $url, $key);
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    public function getKey(): ?string
    {
        if (null === $this->key) {
            $key = openssl_pkey_new([
                'private_key_bits' => 4096,
                'private_key_type' => OPENSSL_KEYTYPE_RSA,
            ]);
            if ($key) {
                $keyString = '';
                openssl_pkey_export($key, $keyString);
            } else {
                throw new \Exception('Unable to generate private key');
            }
            $this->key = $keyString;
        }

        return $this->key;
    }
}
