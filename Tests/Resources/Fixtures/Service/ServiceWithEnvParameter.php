<?php

declare(strict_types=1);

namespace FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service;

use FRZB\Component\DependencyInjection\Attribute\AsService;

/** @internal */
#[AsService(arguments: ['$environment' => '%env(TEST_ENVIRONMENT)%'])]
class ServiceWithEnvParameter implements ServiceInterface
{
    private string $environment;

    public function __construct(string $environment)
    {
        $this->environment = $environment;
    }

    public function getEnvironment(): string
    {
        return $this->environment;
    }
}
