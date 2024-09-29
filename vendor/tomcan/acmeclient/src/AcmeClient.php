<?php

namespace TomCan\AcmeClient;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use TomCan\AcmeClient\Interfaces\AccountInterface;
use TomCan\AcmeClient\Interfaces\AuthorizationInterface;
use TomCan\AcmeClient\Interfaces\CertificateInterface;
use TomCan\AcmeClient\Interfaces\ChallengeInterface;
use TomCan\AcmeClient\Interfaces\OrderInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AcmeClient
{
    private HttpClientInterface $httpClient;
    private string $directoryUrl;
    /** @var string[]|null  */
    private ?array $directory = null;
    private ?string $nonce = null;

    private AccountInterface $account;
    /** @var \OpenSSLAsymmetricKey|false */
    private $accountKey;
    /** @var mixed[] */
    private array $accountKeyDetails;
    private string $accountKeyThumbprint;

    /** @var string[] */
    private array $classes;

    private ?LoggerInterface $logger;

    /**
     * @param string[] $classes
     */
    public function __construct(
        HttpClientInterface $httpClient,
        string $directoryUrl = 'https://acme-v02.api.letsencrypt.org/directory',
        array $classes = [],
        ?LoggerInterface $logger = null,
    ) {
        $this->httpClient = $httpClient;
        $this->directoryUrl = $directoryUrl;
        $this->logger = $logger;

        $this->classes = [];
        foreach (['account', 'authorization', 'challenge', 'order', 'certificate'] as $type) {
            $interface = 'TomCan\AcmeClient\Interfaces\\'.ucfirst($type).'Interface';
            if (isset($classes[$type])) {
                $implemented = @class_implements($classes[$type]);
                if (!is_array($implemented) || !in_array($interface, $implemented)) {
                    $this->log('error', 'Class {classname} does not implement {interface}', ['classname' => $classes[$type], 'interface' => $interface]);
                    throw new \Exception('Class '.$classes[$type].' does not implement '.$interface);
                } else {
                    $this->log('notice', 'Using {classname} for {interface}', ['classname' => $classes[$type], 'interface' => $interface]);
                    $this->classes[$type] = $classes[$type];
                }
            } else {
                // default to our objects
                $this->classes[$type] = 'TomCan\AcmeClient\Objects\\'.ucfirst($type);
                $this->log('debug', 'Using {classname} for {interface}', ['classname' => $this->classes[$type], 'interface' => $interface]);
            }
        }
    }

    /**
     * @param mixed[] $context
     */
    private function log(string $level, string $message, array $context = []): void
    {
        if (null !== $this->logger) {
            $this->logger->log($level, 'TomCan\AcmeClient: '.$message, $context);
        }
    }

    private function getDirectory(string $method): string
    {
        if (null === $this->directory) {
            $response = $this->httpClient->request('GET', $this->directoryUrl);
            $this->directory = json_decode($response->getContent(), true);
        }

        return $this->directory[$method];
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * @param mixed[]|null $payload
     */
    private function makeRequest(string $method, string $url, ?array $payload = null): ResponseInterface
    {
        $this->log('info', 'Making request: {method} {url}', ['method' => $method, 'url' => $url]);
        if ('POST' == $method) {
            if (null === $this->nonce) {
                // get initial/new nonce
                $this->log('notice', 'No nonce. need to request one first');
                $this->makeRequest('HEAD', $this->getDirectory('newNonce'));
                if (null === $this->nonce) {
                    throw new \Exception('Unable to get nonce');
                }
            }
            if ($url == $this->getDirectory('newAccount')) {
                // newAccount used JWS JWK
                $response = $this->httpClient->request($method, $url, [
                    'headers' => [
                        'Content-Type' => 'application/jose+json',
                    ],
                    'json' => $this->signPayloadJWK($payload, $url),
                ]);
            } else {
                // the other calls use JWS KID
                $response = $this->httpClient->request($method, $url, [
                    'headers' => [
                        'Content-Type' => 'application/jose+json',
                    ],
                    'json' => $this->signPayloadKID($payload, $url),
                ]);
            }
        } else {
            $response = $this->httpClient->request($method, $url);
        }

        // extract new nonce from response and save for next request
        $headers = $response->getHeaders();
        if (isset($headers['replay-nonce'])) {
            $this->nonce = $headers['replay-nonce'][0];
            $this->log('debug', 'Saving nonce {nonce}', ['nonce' => $this->nonce]);
        }

        $this->log('debug', "response headers:\n {headers}", ['headers' => print_r($response->getHeaders(), true)]);
        $this->log('debug', "response body:\n{body}", ['body' => $response->getContent()]);

        return $response;
    }

    public function getAccount(AccountInterface $account): AccountInterface
    {
        $this->account = $account;
        // Open key and extract all required information
        $this->accountKey = openssl_pkey_get_private($this->account->getKey());
        if ($this->accountKey) {
            $details = openssl_pkey_get_details($this->accountKey);
            if ($details) {
                $this->accountKeyDetails = $details;
                // SHA-256 hash of JWK Thumbprint
                $this->accountKeyThumbprint = $this->base64UrlEncode(hash('sha256', (string) json_encode([
                    'e'   => $this->base64UrlEncode($this->accountKeyDetails['rsa']['e']),
                    'kty' => 'RSA',
                    'n'   => $this->base64UrlEncode($this->accountKeyDetails['rsa']['n']),
                ]), true));
            }
        }

        if (!$this->accountKey || !$this->accountKeyDetails || !$this->accountKeyThumbprint) {
            throw new \Exception('Unable to open private key');
        }

        $response = $this->makeRequest(
            'POST',
            $this->getDirectory('newAccount'),
            [
                'termsOfServiceAgreed' => true,
                'contact' => [
                    'mailto:'.$account->getEmail(),
                ],
            ]
        );

        if (null === $account->getUrl()) {
            // new account, create new account object from response
            $this->account->setUrl($response->getHeaders()['location'][0]);
        }

        return $this->account;
    }

    /**
     * @param string[] $domains
     */
    public function createOrder(array $domains): OrderInterface
    {
        $items = [];
        foreach ($domains as $domain) {
            $items[] = [
                    'type'  => 'dns',
                    'value' => $domain,
            ];
        }

        $response = $this->makeRequest(
            'POST',
            $this->getDirectory('newOrder'),
            ['identifiers' => $items],
        );

        $headers = $response->getHeaders();
        $data = json_decode($response->getContent());

        $identifiers = [];
        foreach ($data->identifiers as $identifier) {
            $identifiers[] = $identifier->value;
        }

        $className = $this->classes['order'];
        /** @var OrderInterface $order */
        $order = $className::create(
            $headers['location'][0],
            $data->status,
            new \DateTime($data->expires),
            $identifiers,
            $data->authorizations,
            $data->finalize,
        );

        return $order;
    }

    /**
     * @return AuthorizationInterface[]
     */
    public function authorize(OrderInterface $order): array
    {
        $authorizations = [];
        foreach ($order->getAuthorizations() as $url) {
            /** @var AuthorizationInterface $authorization */
            $authorization = $this->getAuthorization($url);
            $authorizations[] = $authorization;
        }

        return $authorizations;
    }

    public function getAuthorization(string $url): AuthorizationInterface
    {
        $authorizationClass = $this->classes['authorization'];
        $challengeClass = $this->classes['challenge'];

        $response = $this->makeRequest(
            'POST',
            $url,
            null
        );
        $data = json_decode($response->getContent());
        $challenges = [];
        foreach ($data->challenges as $challenge) {
            $challenges[$challenge->type] = $challengeClass::create(
                $challenge->type,
                $challenge->status,
                $challenge->url,
                $challenge->token,
                'http-01' == $challenge->type ? $challenge->token.'.'.$this->accountKeyThumbprint : $this->base64UrlEncode(hash('sha256', $challenge->token.'.'.$this->accountKeyThumbprint, true))
            );
        }
        // string $url, string $identifier, string $status, \DateTime $expires, array $challenges
        /** @var AuthorizationInterface $authorization */
        $authorization = $authorizationClass::create(
            $url,
            $data->identifier->value,
            $data->status,
            \DateTime::createFromFormat('Y-m-d\TH:i:s\Z', $data->expires, new \DateTimeZone('UTC')),
            $challenges
        );

        return $authorization;
    }

    /**
     * @param AuthorizationInterface[] $authorizations
     * @param ChallengeInterface[] $challenges
     */
    public function validate(array $authorizations, array $challenges, int $pollAttempts = 10, int $pollInterval = 2): bool
    {
        // Request all validations
        $pendingChallenges = [];
        for ($i = 0; $i < count($authorizations); $i++) {
            $authorization = $authorizations[$i];
            // check if state is pending
            if ('pending' == $authorization->getStatus()) {
                $challenge = $challenges[$i];
                if ('pending' == $challenge->getStatus()) {
                    $pendingChallenges[$challenge->getToken()] = $challenge;
                    // request validation
                    $response = $this->makeRequest(
                        'POST',
                        $challenge->getUrl(),
                        $this->signPayloadKID(
                            ['keyAuthorization' => $challenge->getToken() . '.' . $this->accountKeyThumbprint],
                            $challenge->getUrl()
                        )
                    );
                    if (200 != $response->getStatusCode()) {
                        throw new \Exception('Unable to validate challenge');
                    }
                }
            }
        }

        // Check status of autharizations and challenges
        $attempt = 0;
        while (count($pendingChallenges) > 0 && $attempt < $pollAttempts) {
            ++$attempt;
            sleep($pollInterval);

            for ($i = 0; $i < count($authorizations); $i++) {
                // check status of authorization
                $authorization = $authorizations[$i];
                $authorizationFetched = $this->getAuthorization($authorization->getUrl());
                if ($authorization->getStatus() != $authorizationFetched->getStatus()) {
                    // update status
                    $authorization->setStatus($authorizationFetched->getStatus());
                }

                foreach ($authorizationFetched->getChallenges() as $challengeFetched) {
                    if (isset($pendingChallenges[$challengeFetched->getToken()])) {
                        $challenge = $pendingChallenges[$challengeFetched->getToken()];
                        if ($challenge->getStatus() != $challengeFetched->getStatus()) {
                            // update status
                            $challenge->setStatus($challengeFetched->getStatus());
                        }
                        // check if this challange still needs to be checked
                        if ('pending' != $authorization->getStatus() || 'pending' != $challenge->getStatus()) {
                            unset($pendingChallenges[$challenge->getToken()]);
                        }
                    }
                }
            }
        }

        foreach ($authorizations as $authorization) {
            if ('valid' !== $authorization->getStatus()) {
                return false;
            }
        }

        return true;
    }

    public function finalize(OrderInterface $order): CertificateInterface
    {
        // generate new key
        $privateKey = openssl_pkey_new([
            'private_key_bits' => 4096,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);

        if ($privateKey) {
            openssl_pkey_export($privateKey, $privateKeyText);
            // create temporary config file
            $configFile = tempnam(sys_get_temp_dir(), 'openssl_');
            if (false === $configFile) {
                throw new \Exception('Unable to generate temporary openssl config file');
            } else {
                // compose content of temporary config file
                $content = "[req]\nreq_extensions = v3_req\ndistinguished_name = dn\ndefault_md = sha512\n[dn]\n[v3_req]\nsubjectAltName = @san\n[san]\n";
                $i = 0;
                foreach ($order->getIdentifiers() as $identifier) {
                    ++$i;
                    $content .= 'DNS.'.$i.' = '.$identifier."\n";
                }

                // write file
                file_put_contents($configFile, $content);

                $csr = openssl_csr_new(
                    [
                        'CN' => $order->getIdentifiers()[0]
                    ],
                    $privateKey,
                    [
                        'config' => $configFile,
                    ]
                );
                unlink($configFile);

                if ($csr) {
                    // convert to der (strip pem header/footer)
                    openssl_csr_export($csr, $csrText, true);
                    $csrLines = explode("\n", str_replace("\r", '', trim($csrText)));
                    $csrLines = array_slice($csrLines, 1, count($csrLines) -2);
                    $der = base64_decode(implode('', $csrLines));

                    if ($der) {
                        $response = $this->makeRequest(
                            'POST',
                            $order->getFinalize(),
                            ['csr' => $this->base64UrlEncode($der)]
                        );
                        $data = json_decode($response->getContent());

                        // keep polling until status is no longer 'processing'
                        while ($data && 'processing' == $data->status) {
                            sleep(1);
                            $response = $this->makeRequest(
                                'POST',
                                $order->getUrl(),
                                null
                            );
                            $data = json_decode($response->getContent());
                        }

                        // check to see if status is 'valid'
                        if ($data && 'valid' == $data->status) {
                            $response = $this->makeRequest(
                                'POST',
                                $data->certificate,
                                null
                            );
                            $certText = $response->getContent();

                            $className = $this->classes['certificate'];
                            /** @var CertificateInterface $certificate */
                            $certificate = $className::create(
                                $privateKeyText,
                                $csrText,
                                $certText
                            );

                            return $certificate;
                        } else {
                            throw new \Exception('Unable to finalize order');
                        }
                    } else {
                        throw new \Exception('Unable to convert csr to der');
                    }
                } else {
                    throw new \Exception('Unable to generate csr');
                }
            }
        } else {
            throw new \Exception('Unable to generate private key');
        }
    }

    /**
     * START OF JWK functions
     */

    /**
     * @param mixed[]|null $payload
     * @return string[]
     */
    private function signPayloadJWK(?array $payload, string $url): array
    {
        $payload = str_replace('\\/', '/', (string) json_encode($payload));
        $payload = $this->base64UrlEncode($payload);
        $protected = $this->base64UrlEncode((string) json_encode($this->getJWKEnvelope($url)));

        if ($this->accountKey) {
            if (false === openssl_sign($protected.'.'.$payload, $signature, $this->accountKey, "SHA256")) {
                throw new \Exception('Could not generate signature');
            }
        } else {
            throw new \Exception('Private key not loaded');
        }

        return [
            'protected' => $protected,
            'payload'   => $payload,
            'signature' => $this->base64UrlEncode($signature),
        ];
    }

    /**
     * @return mixed[]
     */
    private function getJWKEnvelope(string $url): array
    {
        return [
            'alg'   => 'RS256',
            'jwk'   => [
                'e'   => $this->base64UrlEncode($this->accountKeyDetails['rsa']['e']),
                'kty' => 'RSA',
                'n'   => $this->base64UrlEncode($this->accountKeyDetails['rsa']['n']),
            ],
            'nonce' => $this->nonce,
            'url'   => $url
        ];
    }

    /**
     * @param mixed[]|null $payload
     * @return mixed[]
     */
    private function signPayloadKID(?array $payload, string $url): array
    {
        if (null === $payload) {
            // GET-as-POST with empty payload
            $payload = '';
        } else {
            $payload = str_replace('\\/', '/', (string) json_encode($payload));
        }
        $payload = $this->base64UrlEncode($payload);
        $protected = $this->base64UrlEncode((string) json_encode($this->getKIDEnvelope($url)));

        if ($this->accountKey) {
            if (false === openssl_sign($protected.'.'.$payload, $signature, $this->accountKey, "SHA256")) {
                throw new \Exception('Could not generate signature');
            }
        } else {
            throw new \Exception('Private key not loaded');
        }

        return [
            'protected' => $protected,
            'payload'   => $payload,
            'signature' => $this->base64UrlEncode($signature),
        ];
    }

    /**
     * @return string[]
     */
    private function getKIDEnvelope(string $url): array
    {
        return [
            "alg"   => "RS256",
            "kid"   => $this->account->getUrl(),
            "nonce" => $this->nonce,
            "url"   => $url
        ];
    }

    /**
     * END OF JWK functions
     */
}
