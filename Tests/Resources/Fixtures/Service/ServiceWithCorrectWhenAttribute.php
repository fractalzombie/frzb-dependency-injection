<?php

declare(strict_types=1);

namespace FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service;

use FRZB\Component\DependencyInjection\Attribute\AsService;
use Symfony\Component\DependencyInjection\Attribute\When;

/** @internal */
#[When('test')]
#[AsService]
final class ServiceWithCorrectWhenAttribute implements ServiceInterface
{
}
