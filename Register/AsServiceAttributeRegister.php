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

use FRZB\Component\DependencyInjection\Attribute\AsService;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Register #[AsService] attribute on definition that is autoconfigured.
 *
 * @author Mykhailo Shtanko <fractalzombie@gmail.com>
 */
class AsServiceAttributeRegister extends AbstractAttributeRegister
{
    public function register(ContainerBuilder $container, \ReflectionClass $rClass, \ReflectionAttribute $rAttribute): void
    {
        $attribute = self::getAttribute(AsService::class, $rAttribute);
        $definition = $container->getDefinition($rClass->getName());

        $definition
            ->setClass($definition->getClass())
            ->setShared($attribute->isShared() ?? $definition->isShared())
            ->setSynthetic($attribute->isSynthetic() ?? $definition->isSynthetic())
            ->setLazy($attribute->isLazy() ?? $definition->isLazy())
            ->setPublic($attribute->isPublic() ?? $definition->isPublic())
            ->setAbstract($attribute->isAbstract() ?? $definition->isAbstract())
            ->setFactory($attribute->getFactory() ?? $definition->getFactory())
            ->setFile($attribute->getFile() ?? $definition->getFile())
            ->setArguments(array_merge($definition->getArguments(), $attribute->getArguments()))
            ->setProperties(array_merge($definition->getProperties(), $attribute->getProperties()))
            ->setConfigurator($attribute->getConfigurator() ?? $definition->getConfigurator())
            ->setMethodCalls(array_merge($definition->getMethodCalls(), $attribute->getCalls()))
            ->setTags(array_merge($definition->getTags(), $attribute->getTags()))
            ->setAutowired($attribute->isAutowire() ?? $definition->isAutowired())
            ->setAutoconfigured($attribute->isAutoconfigured() ?? $definition->isAutoconfigured())
            ->setBindings(array_merge($definition->getBindings(), $attribute->getBindings()))
        ;

        $container->setDefinition($definition->getClass(), $definition);
    }
}
