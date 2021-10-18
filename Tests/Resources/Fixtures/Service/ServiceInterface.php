<?php

declare(strict_types=1);

namespace FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service;

use FRZB\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(Service::class)]
#[AsAlias(ServiceWithEnvParameter::class, aliasForArgument: '$serviceWithArgument')]
#[AsAlias(DeprecatedService::class, aliasForArgument: '$serviceDeprecated')]
interface ServiceInterface
{
}
