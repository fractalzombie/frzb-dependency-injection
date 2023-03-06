#[AsDeprecated] Attribute
=============================

How to use it:
-------------

Attribute `#[AsDeprecated]` solves cases when we need to deprecate service

```php
namespace App\Http\Service;

use FRZB\Component\DependencyInjection\Attribute\AsDeprecated;

#[AsDeprecated(message: )]
class Service
{
    public function __construct() {}
}
```
