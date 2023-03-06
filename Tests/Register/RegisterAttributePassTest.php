<?php

declare(strict_types=1);

namespace FRZB\Component\DependencyInjection\Tests\Register;

use FRZB\Component\DependencyInjection\Compiler\RegisterAsAliasAttributesPass;
use FRZB\Component\DependencyInjection\Compiler\RegisterAsDeprecatedAttributesPass;
use FRZB\Component\DependencyInjection\Compiler\RegisterAsIgnoredAttributesPass;
use FRZB\Component\DependencyInjection\Compiler\RegisterAsServiceAttributePass;
use FRZB\Component\DependencyInjection\Compiler\RegisterAsTaggedAttributesPass;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\AnotherService;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\AnotherServiceInterface;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\DecoratedService;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\DeprecatedService;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\IgnoredService;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\IgnoredServiceInterface;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\NamedService;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\NamedServiceInterface;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\Service;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\ServiceInterface;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\ServiceWithCorrectWhenAttribute;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\ServiceWithEnvParameter;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\ServiceWithWhenAttribute;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\TaggedService;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\TaggedServiceInterface;
use FRZB\Component\DependencyInjection\Tests\Util\Helper\ContainerTestCase;
use FRZB\Component\DependencyInjection\Tests\Util\Helper\TestConstant;
use PHPUnit\Framework\Attributes\Group;

/** @internal */
#[Group('dependency-injection')]
/**
 * @internal
 */
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

        self::assertTrue($this->hasDefinition(Service::class));
        self::assertTrue($this->hasDefinition(AnotherService::class));
        self::assertSame(TestConstant::TEST_ENVIRONMENT, $this->get(ServiceWithEnvParameter::class)?->getEnvironment());
    }

    public function testAliasRegistrationInContainer(): void
    {
        $this->addCompilerPasses(
            new RegisterAsServiceAttributePass(),
            new RegisterAsAliasAttributesPass(),
        );

        $this->compileContainer();

        self::assertInstanceOf(Service::class, $this->get(ServiceInterface::class));
        self::assertInstanceOf(AnotherService::class, $this->get(AnotherServiceInterface::class));
        self::assertInstanceOf(ServiceWithEnvParameter::class, $this->get(TestConstant::TEST_SERVICE_WITH_ARGUMENT_FULL_NAME));
        self::assertFalse($this->hasDefinition(ServiceWithWhenAttribute::class));
        self::assertTrue($this->hasDefinition(ServiceWithCorrectWhenAttribute::class));
        self::assertNotEmpty($this->getTags(AnotherServiceInterface::class));
    }

    public function testNamedServicesWithAliasRegistrationInContainer(): void
    {
        $this->addCompilerPasses(
            new RegisterAsServiceAttributePass(),
            new RegisterAsTaggedAttributesPass(),
            new RegisterAsAliasAttributesPass(),
        );

        $this->compileContainer();

        $firstNamedServiceAlias = $this->get('FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\NamedServiceInterface $first');
        $secondNamedServiceAlias = $this->get('FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\NamedServiceInterface $second');
        $firstNamedService = $this->get('named_service.first');
        $secondNamedService = $this->get('named_service.second');

        self::assertInstanceOf(NamedService::class, $firstNamedServiceAlias);
        self::assertInstanceOf(NamedService::class, $secondNamedServiceAlias);
        self::assertInstanceOf(NamedService::class, $secondNamedService);
        self::assertInstanceOf(NamedService::class, $secondNamedService);
        self::assertSame('first', $firstNamedServiceAlias->getType());
        self::assertSame('second', $secondNamedServiceAlias->getType());
        self::assertSame('first', $firstNamedService->getType());
        self::assertSame('second', $secondNamedService->getType());
        self::assertTrue($this->hasDefinition('named_service.first'));
        self::assertTrue($this->hasDefinition('named_service.second'));
        self::assertNotEmpty($this->getTags(NamedServiceInterface::class));
    }

    public function testDeprecatedRegistrationInContainer(): void
    {
        $this->addCompilerPasses(
            new RegisterAsServiceAttributePass(),
            new RegisterAsDeprecatedAttributesPass(),
        );

        $this->compileContainer();

        self::assertTrue($this->hasDefinition(DeprecatedService::class));
        self::assertSame(
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

        self::assertTrue($this->hasDefinition(DecoratedService::class));
    }

    public function testTaggedRegistrationInContainer(): void
    {
        $this->addCompilerPasses(
            new RegisterAsServiceAttributePass(),
            new RegisterAsTaggedAttributesPass(),
            new RegisterAsAliasAttributesPass(),
        );

        $this->compileContainer();

        self::assertTrue($this->hasDefinition(TaggedService::class));
        self::assertTrue($this->hasAlias(TaggedServiceInterface::class));
        self::assertNotEmpty($this->getTags(TaggedServiceInterface::class));
    }

    public function testIgnoredRegistrationInContainer(): void
    {
        $this->addCompilerPasses(
            new RegisterAsServiceAttributePass(),
            new RegisterAsTaggedAttributesPass(),
            new RegisterAsAliasAttributesPass(),
            new RegisterAsIgnoredAttributesPass(),
        );

        $this->compileContainer();

        self::assertFalse($this->hasDefinition(IgnoredService::class));
        self::assertFalse($this->hasAlias(IgnoredServiceInterface::class));
    }
}
