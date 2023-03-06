<?php

declare(strict_types=1);

namespace FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service;

use FRZB\Component\DependencyInjection\Attribute\AsDecorator;
use FRZB\Component\DependencyInjection\Attribute\AsService;
use JetBrains\PhpStorm\Pure;

/** @internal */
#[AsService]
#[AsDecorator(decorates: Service::class, innerName: '@.inner')]
class DecoratedService
{
    private Service $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    #[Pure]
    public function getService(): ServiceInterface
    {
        return $this->service;
    }
}
