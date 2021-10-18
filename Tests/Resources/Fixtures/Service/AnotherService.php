<?php

declare(strict_types=1);

namespace FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service;

use FRZB\Component\DependencyInjection\Attribute\AsService;

#[AsService]
class AnotherService implements AnotherServiceInterface
{
    public const SOMETHING_VALUE = 'something';
    private ServiceInterface $serviceWithArgument;

    public function __construct(ServiceInterface $serviceWithArgument)
    {
        $this->serviceWithArgument = $serviceWithArgument;
    }

    public function getServiceWithArgument(): ServiceInterface
    {
        return $this->serviceWithArgument;
    }

    public function getSomething(): string
    {
        return self::SOMETHING_VALUE;
    }
}
