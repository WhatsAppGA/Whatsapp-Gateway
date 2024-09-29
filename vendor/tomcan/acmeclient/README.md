# AcmeClient library

AcmeClient library is PHP library implementing the Automatic Certificate Management Environment (ACME) protocol,
used for requesting certificates from services like Let's Encrypt.

The goal of the project is to create a flexible library that allows you to incorporate certificate registration 
through ACME in your own application (e.g. SaaS service with customer domains), without the need of an external tool or
client. 

The library uses interfaces for both what it requires as what it returns, so you have the freedom to implement these
interfaces into the already existing classes in your own product. If you don't require that much of an integration, you
can also use the built-in classes that can provide the required functionality to get the job done. 

Note: the actual validation is not part of the scope of this library. It will tell you what http or DNS challenge 
to provide, but it's up to you to make sure the given values end up where they need to end up.

# (in)completeness

This does not aim to be a full implementation of the RFC, but mainly focuses on the flow of obtaining certificate
from ACME compatible services (e.g. Let's Encrypt).

## Implemented functionality

- Account creation
- Order creation
- Order authorization
  - Get authorizations
  - Get challenges
- Order validation
- Order finalization (get actual certificate)

# Getting started

To get started, you'll need to add the library to your composer-based project.

```bash
composer require tomcan/acmeclient
```

The client requires a HTTP Client that implements the Symfony\HttpClientInterface (eg. the Symfony HttpClient), as well
as the directory URL of the ACME server (defaults to Let's Encrypt), and optionally a logger class implementing the
Psr\LoggerInterface interface.

```php
$httpClient = \Symfony\Component\HttpClient\HttpClient::create();
$acmeClient = new \TomCan\AcmeClient\AcmeClient($httpClient, 'https://acme-staging-v02.api.letsencrypt.org/directory');
```

Next, you will need an account on the ACME server. This requires an e-mail address and a RSA key that will be used for
signing the requests. If you're using the built-in Account class, it will automatically generate a new key if none is
provided. You then need to validate the account with the directory. If it's a new account, it will automatically create
the account and return it.

```php
// Create new account object
/** @var AccountInterface $account */
$account = new Account('your@email-address.here', null, null);
$acmeClient->getAccount($account);
```

You should save/persist the account information for later use. This could be a simple as writing the info to a json file,
or persisting it to database through an ORM. But that's totally up to you to implement that.
```php
// Example for your convenience only. 

// save Account after having called $acmeClient->getAccount($account) to create the account
$acmeClient->getAccount($account);
file_put_contents('/path/to/account.json', json_encode(['email' => $account->getEmail(), 'url' => $account->getEmail(), 'key' => $account->getKey()]));

// re-loading the account on later requests
$accountData = json_decode(file_get_contents('/path/to/account.json));
$account = new Account($accountData->email, $accountData->url, $accountData->key);
$acmeClient->getAccount($account);
```

Once you have the account, you can create an order, passing an array of domainnames as identifiers. You can then need to
authorize the order to obtain the list of authorizations and challenges to complete. You probably want to do some
pre-flight checks of your own before authorizing the order, as every authorization needs to be successful in order to
get the certificate.

```php
/** @var OrderInterface */
$order = $acmeClient->createOrder(['yourdomain.com', 'www.yourdomain.com']);
/** @var AuthorizationInterface[] */
$authorizations = $acmeClient->authorize($order);
```

The returned AuthorizationInterface array contains an autorization object for every requested domain. For each authorization
at least one challenge needs to be validated. Although multiple challenges are returned, you only need to validate one
challenge for each authorization. This can be a HTTP challenge or a DNS challenge. You need to pass both the autorizations
as the choosen challenge as arrays, where the index of the authorizations array corresponds to the index of the challenge.

For HTTP challenges, a request will be made to http://{hostname}/.well-known/acme-challenge/{token}. You can obtain
the value of token using the getToken() method on the ChallengeInterface object, and the required content using the 
getValue() method. 

For DNS challenges, a DNS TXT record has to be created as _acme-challenge.{hostname}. The value of the DNS record can be
obtained using the getValue method on the ChallengeInterface object.

```php
// example of using the http-01 HTTP challenge
$challenges = [];
foreach ($authorizations as $authorization) {
    $a[] = $authorization;
    foreach ($authorization->getChallenges() as $challenge) {
        if ($challenge->getType() == 'http-01') {
            $challenges[] = $challenge;
            // e.g. write file to documentroot of webserver
            file_put_contents('/path/to/documentroot/.well-known/acme-challenge/'.$challenge->getToken(), $challenge->getValue());
        }
    }
}
$result = $acmeClient->validate($authorizations, $challenges);
```

The validate method will return true when all authorizations have been completed, or false if failed. The authorizations
and challenges passed to the method will be updated to reflect the status, so you can use them to determine which ones
actually succeeded of failed.

If all authorizations are completed, you can finalize the order and obtain the certificate. The client will generate a 
new private key and csr and pass that to the ACME server. The private key, csr and complete certificate chain will be
returned through the CertificateInterface object.

```php
/** @var CertificateInterface
$cert = $acmeClient->finalize($order);
```

# References
https://datatracker.ietf.org/doc/html/rfc8555
