<?php

declare(strict_types=1);

namespace FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service;

use FRZB\Component\DependencyInjection\Attribute\AsService;

/** @internal */
#[AsService]
final class Service implements ServiceInterface
{
    private AnotherServiceInterface $anotherService;

    public function __construct(AnotherServiceInterface $anotherService)
    {
        $this->anotherService = $anotherService;
    }

    public function getAnotherService(): AnotherServiceInterface
    {
        return $this->anotherService;
    }
}
