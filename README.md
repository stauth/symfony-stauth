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

Initialize Bundle in `AppKernel.php`:

```php

        if ($this->getEnvironment() !== 'prod') {
            // ...
            $bundles[] = new Stauth\StauthProtectionBundle();
        }
```

### Production

If you don't mind Stauth service provider being executed at production environment, or you want to protect your production app, import routes in `app/routing.yml`

```yaml
# stauth protection
stauth_protection:
    resource: "@StauthProtectionBundle/Resources/config/routing.yml"
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

Generate access token at [stauth.io](https://www.stauth.io) and add it to `parameteres.yml`:

```yaml
stauth.access_token: 'eyJkkk40eXAiOiJsdvk3k43krKV1QiLCJ...'
```

By default protected environment is `staging`, in order to change this, add `stauth.protected_env` to `parameters.yml`: 

```bash
stauth.protected_env: 'local'
```

### Cache

Please keep in mind that this package takes adventage of `csrf_token`, therefore it is important to exclude both routes `/stauth/protected` and `/stauth/authorize` from any response caching engines.
