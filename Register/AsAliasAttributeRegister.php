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

namespace FRZB\Component\DependencyInjection\Register;

use FRZB\Component\DependencyInjection\Attribute\AsAlias;
use FRZB\Component\DependencyInjection\Exception\AliasAttributeLogicException;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Register #[AsAlias] attribute on alias that is autoconfigured.
 *
 * @author Mykhailo Shtanko <fractalzombie@gmail.com>
 */
class AsAliasAttributeRegister extends AbstractAttributeRegister
{
    public function register(ContainerBuilder $container, \ReflectionClass $rClass, \ReflectionAttribute $rAttribute): void
    {
        $environment = $container->getParameter('kernel.environment');
        $attribute = self::getAttribute(AsAlias::class, $rAttribute);

        if ($attribute && !self::isPermittedEnvironmentOrEnvironmentIsNotDefined($environment, $attribute->getService())) {
            return;
        }

        try {
            $definitionClass = $container->getReflectionClass($attribute->getService());
        } catch (\ReflectionException $e) {
            throw AliasAttributeLogicException::noDefinitionInContainer($attribute, $e);
        }

        if ($rClass->isInterface() && !$definitionClass?->isSubclassOf($rClass->getName())) {
            throw AliasAttributeLogicException::invalidImplementation($attribute, $rClass);
        }

        match ($attribute->getAliasState()) {
            $attribute::WITH_ARGUMENT_NAME => $this->registerAliasWithArgument($container, $rClass, $attribute),
            $attribute::WITHOUT_ARGUMENT_NAME => $this->registerAliasWithoutArgument($container, $rClass, $attribute),
            $attribute::LOGIC_EXCEPTION => throw AliasAttributeLogicException::unexpected($attribute),
        };
    }

    private function registerAliasWithArgument(ContainerBuilder $container, \ReflectionClass $rClass, AsAlias $attribute): void
    {
        $container
            ->registerAliasForArgument($attribute->getService(), $rClass->getName(), $attribute->getAliasForArgument())
            ->setPublic($attribute->isPublic())
        ;
    }

    private function registerAliasWithoutArgument(ContainerBuilder $container, \ReflectionClass $rClass, AsAlias $attribute): void
    {
        $container
            ->setAlias($rClass->getName(), $attribute->getService())
            ->setPublic($attribute->isPublic())
        ;
    }
}
