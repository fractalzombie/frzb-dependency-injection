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
use FRZB\Component\DependencyInjection\Helper\DefinitionHelper;
use FRZB\Component\DependencyInjection\Helper\EnvironmentHelper;
use Symfony\Component\DependencyInjection\ContainerBuilder;

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

    public function register(ContainerBuilder $container, \ReflectionClass $reflectionClass, AsAlias $attribute): void
    {
        if (!EnvironmentHelper::isPermittedEnvironment($container, $reflectionClass)) {
            return;
        }

        try {
            $definitionClass = DefinitionHelper::getReflectionClassForServiceId($container, $attribute->service);
        } catch (\ReflectionException $e) {
            throw AttributeException::noDefinitionInContainer($attribute, $e);
        }

        if ($reflectionClass->isInterface() && !$definitionClass?->isSubclassOf($reflectionClass->getName())) {
            throw AttributeException::invalidImplementation($attribute, $reflectionClass);
        }

        match ($attribute->aliasType) {
            AliasType::WithArgumentName => $this->registerAliasWithArgument($container, $reflectionClass, $attribute),
            AliasType::WithoutArgumentName => $this->registerAliasWithoutArgument($container, $reflectionClass, $attribute),
            AliasType::LogicException => throw AttributeException::unexpected($attribute),
        };
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
