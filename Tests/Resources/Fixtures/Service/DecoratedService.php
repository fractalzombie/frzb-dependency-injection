<?php

declare(strict_types=1);


namespace FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service;

use FRZB\Component\DependencyInjection\Attribute\AsDecorated;
use FRZB\Component\DependencyInjection\Attribute\AsService;
use JetBrains\PhpStorm\Pure;

#[AsService]
#[AsDecorated(decorates: Service::class, decorationInnerName: '@.inner')]
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
