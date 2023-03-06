#[AsDecorator] Attribute
=============================

How to use it:
-------------

Attribute `#[AsDecorator]` solves cases when we need to decorate other service

```php
namespace App\Http\Service;

use FRZB\Component\DependencyInjection\Attribute\AsDecorator;
use FRZB\Component\DependencyInjection\Attribute\AsService;

#[AsService]
class Service
{
    public function __construct() {}
}

#[AsDecorator(Service::class)]
class DecoratedService
{
    public function __construct() {}
}
```
