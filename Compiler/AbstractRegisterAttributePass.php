<?php

declare(strict_types=1);

namespace FRZB\Component\DependencyInjection\Compiler;

use Fp\Collections\ArrayList;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Registers services by attributes in DI.
 *
 * @author Mykhailo Shtanko <fractalzombie@gmail.com>
 *
 * @method register(ContainerBuilder $container, \ReflectionClass $rClass, \Attribute $attribute): void
 */
abstract class AbstractRegisterAttributePass implements CompilerPassInterface
{
    /** @var class-string */
    private string $attributeClass;

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
        ArrayList::collect($class->getAttributes($this->attributeClass, \ReflectionAttribute::IS_INSTANCEOF))
            ->tap(fn (\ReflectionAttribute $attribute) => $this->register($container, $class, $attribute->newInstance()))
        ;
    }

    abstract protected function accept(Definition $definition): bool;

    protected function isAttributesIgnored(Definition $definition): bool
    {
        return !$definition->hasTag('container.ignore_attributes');
    }
}
