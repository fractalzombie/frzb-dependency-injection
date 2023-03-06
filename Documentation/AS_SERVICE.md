#[AsService] Attribute
=============================

How to use it:
-------------

For now, we can register service definitions using autowiring, but in some cases
we need to put `ENV` variable or something `dynamic` as dependency,
but we can do it only throught the yaml or xml configuration file.
Attribute `#[AsService]` will solve this issue.

Imagine that we have .env file:
```dotenv
HTTP_CLIENT_HOST=https://localhost
HTTP_CLIENT_PORT=8080
HTTP_CLIENT_TIMEOUT=60
```

Some service that we need to use:
```php
namespace App\Http\Service;

class HttpClient {
    public function __construct(
        private string $host,
        private int $port,
        private int $timeout,
    ) {}
}
```

Earliear we would have to go to a config file and configure it manually for the service:
```yaml
services:
  App\Http\Service\HttpClient:
    arguments:
      $host: '%env(HTTP_CLIENT_HOST)%'
      $port: '%env(HTTP_CLIENT_PORT)%'
      $timeout: '%env(HTTP_CLIENT_TIMEOUT)%'
```

For now we can load all classes from main namespace and exclude what we don't need:
```yaml
services:
  _defaults:
    autowire: true
    autoconfigure: true

  App\:
    resource: '../src/**'
    exclude:
      - '../src/**/{Attribute,Util,Request,Response,ValueObject,DTO,Data,Exception,UseCase,Tests}/**'
      - '../src/*Kernel.php'
```

And then annotate service with attribute `#[AsService]` and it will be work as if you
configure it in the yaml file:

```php
namespace App\Http\Service;

use FRZB\Component\DependencyInjection\Attribute\AsService;

#[AsService(arguments: [
      '$host' => '%env(HTTP_CLIENT_HOST)%',
      '$port' => '%env(HTTP_CLIENT_PORT)%',
      '$timeout' => '%env(HTTP_CLIENT_TIMEOUT)%',
])]
class HttpClient {
    public function __construct(
        private string $host,
        private int $port,
        private int $timeout,
    ) {}
}
```

Or if you want do define id for service:

```php
namespace App\Http\Service;

use FRZB\Component\DependencyInjection\Attribute\AsService;

#[AsService(
    id: 'app.http.service',
    arguments: [
      '$host' => '%env(HTTP_CLIENT_HOST)%',
      '$port' => '%env(HTTP_CLIENT_PORT)%',
      '$timeout' => '%env(HTTP_CLIENT_TIMEOUT)%',
    ]
)]
class HttpClient {
    public function __construct(
        private string $host,
        private int $port,
        private int $timeout,
    ) {}
}
```
