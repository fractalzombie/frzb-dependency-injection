#[AsAlias] Attribute
=============================

How to use it:
-------------

Attribute `#[AsAlias]` solves cases when we need to define interface
as alias for `#[AsService]` attribute

```php
namespace App\Http\Service;

interface ServiceInterface
{
}

class Service implements ServiceInterface
{
    public function __construct() {}
}
```

Earliear we would have to go to a config file and configure it manually for the service:
```yaml
services:
  App\Http\Service\ServiceInterface: '@App\Http\Service\Service'
  App\Http\Service\Service: ~
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

And then annotate service with attribute `#[AsService]` and interface with `#[AsAlias]` after it will be work as if you
configure it in the yaml file.

```php
namespace App\Http\Service;

use FRZB\Component\DependencyInjection\Attribute\AsService;
use FRZB\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(Service::class)
interface ServiceInterface
{
}

#[AsService]
class Service implements ServiceInterface
{
    public function __construct() {}
}
```

Or if service defined with id:

```php
namespace App\Http\Service;

use FRZB\Component\DependencyInjection\Attribute\AsService;
use FRZB\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias('app.http.service')
interface ServiceInterface
{
}

#[AsService(id: 'app.http.service')]
class Service implements ServiceInterface
{
    public function __construct() {}
}
```
