<?php

declare(strict_types=1);

namespace FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service;

use FRZB\Component\DependencyInjection\Attribute\AsAlias;

#[AsAlias(TaggedService::class)]
interface TaggedServiceInterface
{
}
