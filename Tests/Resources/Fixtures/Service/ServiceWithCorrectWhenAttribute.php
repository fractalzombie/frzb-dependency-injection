<?php

declare(strict_types=1);

namespace FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service;

use FRZB\Component\DependencyInjection\Attribute\AsService;
use Symfony\Component\DependencyInjection\Attribute\When;

#[When('test')]
#[AsService()]
class ServiceWithCorrectWhenAttribute implements ServiceInterface
{
}
