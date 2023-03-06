#[AsIgnored] Attribute
=============================

How to use it:
-------------

Attribute `#[AsIgnored]` solves cases when we need to ignore class in container

```php
namespace App\Http\Service;

use FRZB\Component\DependencyInjection\Attribute\AsIgnored;

#[AsIgnored]
class Service
{
    public function __construct() {}
}
```
