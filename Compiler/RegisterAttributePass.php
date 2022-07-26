<?php

declare(strict_types=1);

/*
 * This is package for Symfony framework.
 *
 * (c) Mykhailo Shtanko <fractalzombie@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FRZB\Component\DependencyInjection\Compiler;

use FRZB\Component\DependencyInjection\Attribute\AsAlias;
use FRZB\Component\DependencyInjection\Attribute\AsDecorator;
use FRZB\Component\DependencyInjection\Attribute\AsDeprecated;
use FRZB\Component\DependencyInjection\Attribute\AsService;
use FRZB\Component\DependencyInjection\Attribute\AsTagged;
use FRZB\Component\DependencyInjection\Register\AsAliasAttributeRegister;
use FRZB\Component\DependencyInjection\Register\AsDecoratedAttributeRegister;
use FRZB\Component\DependencyInjection\Register\AsDeprecatedAttributeRegister;
use FRZB\Component\DependencyInjection\Register\AsServiceAttributeRegister;
use FRZB\Component\DependencyInjection\Register\AsTaggedAttributeRegister;
use FRZB\Component\DependencyInjection\Register\AttributeRegisterInterface as AttributeRegister;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Reads attribute from $attributeClass on definitions that are autoconfigured
 * and don't have the "container.ignore_attributes" tag.
 *
 * @author Mykhailo Shtanko <fractalzombie@gmail.com>
 */
class RegisterAttributePass implements CompilerPassInterface
{
    /** @var class-string */
    private string $attributeClass;

    /** @var array<class-string, AttributeRegister> */
    private static array $attributeRegisters = [];

    public function __construct(string $attributeClass)
    {
        $this->attributeClass = $attributeClass;
    }

    public function process(ContainerBuilder $container): void
    {
        foreach ($container->getDefinitions() as $definition) {
            if ($this->accept($definition) && $class = $container->getReflectionClass($definition->getClass(), false)) {
                $this->processClass($container, $class);
            }
        }
    }

    protected function processClass(ContainerBuilder $container, \ReflectionClass $class): void
    {
        foreach ($class->getAttributes($this->attributeClass, \ReflectionAttribute::IS_INSTANCEOF) as $attribute) {
            self::getAttributeRegister($this->attributeClass)->register($container, $class, $attribute->newInstance());
        }
    }

    private function accept(Definition $definition): bool
    {
        return $definition->isAutoconfigured() && !$definition->hasTag('container.ignore_attributes');
    }

    private function getEnvironment(ContainerBuilder $container): string
    {
        return $container->getParameter('kernel.environment');
    }

    /** @param class-string $attributeClass */
    private static function getAttributeRegister(string $attributeClass): AttributeRegister
    {
        $register = match ($attributeClass) {
            AsService::class => AsServiceAttributeRegister::class,
            AsTagged::class => AsTaggedAttributeRegister::class,
            AsDecorator::class => AsDecoratedAttributeRegister::class,
            AsDeprecated::class => AsDeprecatedAttributeRegister::class,
            AsAlias::class => AsAliasAttributeRegister::class,
        };

        return self::$attributeRegisters[$attributeClass] ??= new $register();
    }
}
