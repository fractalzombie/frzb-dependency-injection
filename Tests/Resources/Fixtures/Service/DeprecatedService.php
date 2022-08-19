<?php

declare(strict_types=1);

namespace FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service;

use FRZB\Component\DependencyInjection\Attribute\AsDeprecated;
use FRZB\Component\DependencyInjection\Attribute\AsService;

/** @internal */
#[AsService]
#[AsDeprecated('frzb/dependency-injection', '1.0.0')]
 class DeprecatedService implements ServiceInterface
{
}
