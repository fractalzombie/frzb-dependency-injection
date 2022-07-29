<?php

declare(strict_types=1);

namespace FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service;

use FRZB\Component\DependencyInjection\Attribute\AsAlias;

/** @internal */
#[AsAlias(AnotherService::class)]
interface AnotherServiceInterface
{
    public function getSomething(): string;
}
