# Laravel Stauth
[![Latest Stable Version](https://poser.pugx.org/stauth/symfony-stauth/version)](https://packagist.org/packages/stauth/symfony-stauth)
[![License](https://poser.pugx.org/stauth/symfony-stauth/license)](https://packagist.org/packages/stauth/symfony-stauth)
[![composer.lock available](https://poser.pugx.org/stauth/symfony-stauth/composerlock)](https://packagist.org/packages/stauth/symfony-stauth)

Staging server athorization package, alternative for .htaccess, register at [stauth.io](https://www.stauth.io/)


## Installation


```bash
composer require stauth/symfony-stauth
```

### Local and staging

If you don't want Stauth service provider to be exeuted at production environment, import routes in `app/routing_dev.yml`

```yaml
# stauth protection
stauth_protection:
    resource: "@StauthProtectionBundle/Resources/config/routing.yml"
```

Add stauth access token to `parameteres.yml`:

```yaml
stauth.access_token: 'eyJkkk40eXAiOiJsdvk3k43krKV1QiLCJ...'
```


Initialize Bundle in `AppKernel.php`:

```php

        if ($this->getEnvironment() !== ['prod') {
            // ...
            $bundles[] = new Stauth\StauthProtectionBundle();
        }
```

### Production

If you don't mind Stauth service provider being executed at production environment, or you want to protect your production env, import routes in `app/routing.yml`

```yaml
# stauth protection
stauth_protection:
    resource: "@StauthProtectionBundle/Resources/config/routing.yml"
```

Add stauth access token to `parameteres.yml`:

```yaml
stauth.access_token: 'eyJkkk40eXAiOiJsdvk3k43krKV1QiLCJ...'
```


Initialize Bundle in `AppKernel.php`:

```php


    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Stauth\StauthProtectionBundle(),
        );
            
```

Generate access token at [stauth.io](https://www.stauth.io) and add it as a `STAUTH_ACCESS_TOKEN` param in `.env` file:

```bash
STAUTH_ACCESS_TOKEN=verylongchainoflettersmixedwithsomerandomnumbers123

```

By default protected environment is `staging`, in order to change this, add `STAUTH_PROTECTED_ENV` param in `.env` file: 

```bash
STAUTH_PROTECTED_ENV=local
```

## Other
If you want to know or do more, read below.

### Publish configuration

You can publish configuration and update required params in php file:

```bash

php artisan vendor:publish --provider="Stauth\StauthServiceProvider" --tag=config

```

### Cache

Please keep in mind that this package takes adventage of `csrf_token`, therefore it is important to exclude both routes `/stauth/protected` and `/stauth/authorize` from any response caching engines.