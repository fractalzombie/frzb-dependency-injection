<?php

declare(strict_types=1);

namespace FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service;

use FRZB\Component\DependencyInjection\Attribute\AsService;
use FRZB\Component\DependencyInjection\Attribute\AsTagged;

/** @internal */
#[AsService(id: 'named_service.first', arguments: ['$type' => 'first']), AsTagged(NamedServiceInterface::class, 'named_service.first')]
#[AsService(id: 'named_service.second', arguments: ['$type' => 'second']), AsTagged(NamedServiceInterface::class, 'named_service.second')]
class NamedService implements NamedServiceInterface
{
    public function __construct(
        private readonly string $type,
    ) {
    }

    public function getType(): string
    {
        return $this->type;
    }
}
