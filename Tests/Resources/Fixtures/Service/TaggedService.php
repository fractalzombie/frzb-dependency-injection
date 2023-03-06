<?php

declare(strict_types=1);

namespace FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service;

use FRZB\Component\DependencyInjection\Attribute\AsService;
use FRZB\Component\DependencyInjection\Attribute\AsTagged;

/** @internal */
#[AsService]
#[AsTagged(TaggedServiceInterface::class)]
class TaggedService implements TaggedServiceInterface
{
}
