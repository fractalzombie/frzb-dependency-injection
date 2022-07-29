<?php

declare(strict_types=1);

namespace FRZB\Component\DependencyInjection\Tests\Register;

use FRZB\Component\DependencyInjection\Attribute\AsDecorator;
use FRZB\Component\DependencyInjection\Attribute\AsDeprecated;
use FRZB\Component\DependencyInjection\Attribute\AsService;
use FRZB\Component\DependencyInjection\Attribute\AsTagged;
use FRZB\Component\DependencyInjection\Compiler\RegisterAsAliasAttributesPass;
use FRZB\Component\DependencyInjection\Compiler\RegisterAsDeprecatedAttributesPass;
use FRZB\Component\DependencyInjection\Compiler\RegisterAsServiceAttributePass;
use FRZB\Component\DependencyInjection\Compiler\RegisterAsTaggedAttributesPass;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\AnotherService;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\AnotherServiceInterface;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\DecoratedService;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\DeprecatedService;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\Service;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\ServiceInterface;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\ServiceWithCorrectWhenAttribute;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\ServiceWithEnvParameter;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\ServiceWithWhenAttribute;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\TaggedService;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\TaggedServiceInterface;
use FRZB\Component\DependencyInjection\Tests\Util\Helper\ContainerTestCase;
use FRZB\Component\DependencyInjection\Tests\Util\Helper\TestConstant;

/** @internal */
final class RegisterAttributePassTest extends ContainerTestCase
{
    protected function setUp(): void
    {
        $this->loadServices();
    }

    public function testServiceRegistrationInContainer(): void
    {
        $this->addCompilerPasses(
            new RegisterAsServiceAttributePass(),
        );

        $this->compileContainer();

        static::assertTrue($this->hasDefinition(Service::class));
        static::assertTrue($this->hasDefinition(AnotherService::class));
        static::assertSame(TestConstant::TEST_ENVIRONMENT, $this->get(ServiceWithEnvParameter::class)?->getEnvironment());
    }

    public function testAliasRegistrationInContainer(): void
    {
        $this->addCompilerPasses(
            new RegisterAsServiceAttributePass(),
            new RegisterAsAliasAttributesPass(),
        );

        $this->compileContainer();

        static::assertSame(Service::class, $this->get(ServiceInterface::class)::class);
        static::assertSame(AnotherService::class, $this->get(AnotherServiceInterface::class)::class);
        static::assertSame(ServiceWithEnvParameter::class, $this->get(TestConstant::TEST_SERVICE_WITH_ARGUMENT_FULL_NAME)::class);
        static::assertFalse($this->hasDefinition(ServiceWithWhenAttribute::class));
        static::assertTrue($this->hasDefinition(ServiceWithCorrectWhenAttribute::class));
        static::assertNotEmpty($this->getTags(AnotherServiceInterface::class));
    }

    public function testDeprecatedRegistrationInContainer(): void
    {
        $this->addCompilerPasses(
            new RegisterAsServiceAttributePass(),
            new RegisterAsDeprecatedAttributesPass(),
        );

        $this->compileContainer();

        static::assertTrue($this->hasDefinition(DeprecatedService::class));
        static::assertSame(
            TestConstant::TEST_DEPRECATION_ATTRIBUTE_MESSAGE,
            $this->getDefinition(DeprecatedService::class)->getDeprecation(DeprecatedService::class),
        );
    }

    public function testDecoratedRegistrationInContainer(): void
    {
        $this->addCompilerPasses(
            new RegisterAsServiceAttributePass(),
            new RegisterAsDeprecatedAttributesPass(),
        );

        $this->compileContainer();

        static::assertTrue($this->hasDefinition(DecoratedService::class));
    }

    public function testTaggedRegistrationInContainer(): void
    {
        $this->addCompilerPasses(
            new RegisterAsServiceAttributePass(),
            new RegisterAsTaggedAttributesPass(),
            new RegisterAsAliasAttributesPass(),
        );

        $this->compileContainer();

        static::assertTrue($this->hasDefinition(TaggedService::class));
        static::assertTrue($this->hasAlias(TaggedServiceInterface::class));
        static::assertNotEmpty($this->getTags(TaggedServiceInterface::class));
    }
}
