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
use FRZB\Component\DependencyInjection\Enum\AliasType;
use FRZB\Component\DependencyInjection\Exception\AttributeException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @internal
 *
 * Register #[AsAlias] attribute on alias that is autoconfigured
 *
 * @author Mykhailo Shtanko <fractalzombie@gmail.com>
 */
final class RegisterAsAliasAttributesPass extends AbstractRegisterAttributePass
{
    public function __construct()
    {
        parent::__construct(AsAlias::class);
    }

    /** {@inheritdoc} */
    public function process(ContainerBuilder $container): void
    {
        foreach ($container->getAliases() as $id => $alias) {
            if ($class = $container->getReflectionClass($id, false)) {
                $this->processClass($container, $class);
            }
        }
    }

    public function register(ContainerBuilder $container, \ReflectionClass $rClass, AsAlias $attribute): void
    {
        try {
            $definitionClass = $container->getReflectionClass($attribute->service);
        } catch (\ReflectionException $e) {
            throw AttributeException::noDefinitionInContainer($attribute, $e);
        }

        if ($rClass->isInterface() && !$definitionClass?->isSubclassOf($rClass->getName())) {
            throw AttributeException::invalidImplementation($attribute, $rClass);
        }

        match ($attribute->aliasType) {
            AliasType::WithArgumentName => $this->registerAliasWithArgument($container, $rClass, $attribute),
            AliasType::WithoutArgumentName => $this->registerAliasWithoutArgument($container, $rClass, $attribute),
            AliasType::LogicException => throw AttributeException::unexpected($attribute),
        };
    }

    protected function accept(Definition $definition): bool
    {
        return $definition->isAutoconfigured() && $this->isAttributesIgnored($definition);
    }

    private function registerAliasWithArgument(ContainerBuilder $container, \ReflectionClass $rClass, AsAlias $attribute): void
    {
        $container
            ->registerAliasForArgument($attribute->service, $rClass->getName(), $attribute->aliasForArgument)
            ->setPublic($attribute->isPublic)
        ;
    }

    private function registerAliasWithoutArgument(ContainerBuilder $container, \ReflectionClass $rClass, AsAlias $attribute): void
    {
        $container
            ->setAlias($rClass->getName(), $attribute->service)
            ->setPublic($attribute->isPublic)
        ;
    }
}
