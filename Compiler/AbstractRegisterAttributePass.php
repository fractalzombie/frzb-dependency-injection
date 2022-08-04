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

use Fp\Collections\ArrayList;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @internal
 *
 * Registers services by attributes in DI
 *
 * @author Mykhailo Shtanko <fractalzombie@gmail.com>
 *
 * @method register(ContainerBuilder $container, \ReflectionClass $reflectionClass, \Attribute $attribute): void
 */
abstract class AbstractRegisterAttributePass implements CompilerPassInterface
{
    /** @var class-string */
    private string $attributeClass;

    public function __construct(string $attributeClass)
    {
        $this->attributeClass = $attributeClass;
    }

    /** @throws \ReflectionException */
    public function process(ContainerBuilder $container): void
    {
        foreach ($container->getDefinitions() as $definition) {
            if ($this->accept($definition) && $reflectionClass = $container->getReflectionClass($definition->getClass(), false)) {
                $this->processClass($container, $reflectionClass);
            }
        }
    }

    protected function processClass(ContainerBuilder $container, \ReflectionClass $reflectionClass): void
    {
        ArrayList::collect($reflectionClass->getAttributes($this->attributeClass, \ReflectionAttribute::IS_INSTANCEOF))
            ->tap(fn (\ReflectionAttribute $attribute) => $this->register($container, $reflectionClass, $attribute->newInstance()))
        ;
    }

    protected function accept(Definition $definition): bool
    {
        return $definition->isAutoconfigured() && $this->isAttributesIgnored($definition);
    }

    protected function isAttributesIgnored(Definition $definition): bool
    {
        return !$definition->hasTag('container.ignore_attributes');
    }
}
