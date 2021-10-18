<?php

declare(strict_types=1);

namespace FRZB\Component\DependencyInjection\Tests\Register;

use FRZB\Component\DependencyInjection\Attribute\AsDeprecated;
use FRZB\Component\DependencyInjection\Attribute\AsService;
use FRZB\Component\DependencyInjection\Compiler\RegisterAsAliasAttributesPass;
use FRZB\Component\DependencyInjection\Compiler\RegisterAttributePass;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\AnotherService;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\AnotherServiceInterface;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\DeprecatedService;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\Service;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\ServiceInterface;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\ServiceWithEnvParameter;
use FRZB\Component\DependencyInjection\Tests\Util\Helper\ContainerTestCase;
use FRZB\Component\DependencyInjection\Tests\Util\Helper\TestConstant;

/**
 * @internal
 */
class RegisterAttributePassTest extends ContainerTestCase
{
    protected function setUp(): void
    {
        $this->loadServices();
    }

    public function testServiceRegistrationInContainer(): void
    {
        $this->addCompilerPasses(new RegisterAttributePass(AsService::class));

        $this->compileContainer();

        static::assertTrue($this->hasDefinition(Service::class));
        static::assertTrue($this->hasDefinition(AnotherService::class));
        static::assertSame(TestConstant::TEST_ENVIRONMENT, $this->get(ServiceWithEnvParameter::class)?->getEnvironment());
    }

    public function testAliasRegistrationInContainer(): void
    {
        $this->addCompilerPasses(
            new RegisterAttributePass(AsService::class),
            new RegisterAsAliasAttributesPass(),
        );

        $this->compileContainer();

        static::assertSame(Service::class, $this->get(ServiceInterface::class)::class);
        static::assertSame(AnotherService::class, $this->get(AnotherServiceInterface::class)::class);
        static::assertSame(ServiceWithEnvParameter::class, $this->get(TestConstant::TEST_SERVICE_WITH_ARGUMENT_FULL_NAME)::class);
    }

    public function testDeprecatedRegistrationInContainer(): void
    {
        $this->addCompilerPasses(
            new RegisterAttributePass(AsService::class),
            new RegisterAttributePass(AsDeprecated::class)
        );

        $this->compileContainer();

        static::assertTrue($this->hasDefinition(DeprecatedService::class));
        static::assertSame(
            TestConstant::TEST_DEPRECATION_ATTRIBUTE_MESSAGE,
            $this->getDefinition(DeprecatedService::class)->getDeprecation(DeprecatedService::class)
        );
    }
}
