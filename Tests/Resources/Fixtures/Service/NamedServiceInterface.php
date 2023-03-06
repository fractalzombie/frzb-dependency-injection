<?php

declare(strict_types=1);

namespace FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service;

use FRZB\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias('named_service.first', aliasForArgument: '$first')]
#[AsAlias('named_service.second', aliasForArgument: '$second')]
interface NamedServiceInterface
{
    public function getType(): string;
}
