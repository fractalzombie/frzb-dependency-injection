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
use FRZB\Component\DependencyInjection\Attribute\AsIgnored;
use FRZB\Component\DependencyInjection\Helper\EnvironmentHelper;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @internal
 *
 * Register #[AsAlias] attribute on alias that is autoconfigured
 *
 * @author Mykhailo Shtanko <fractalzombie@gmail.com>
 */
final class RegisterAsIgnoredAttributesPass extends AbstractRegisterAttributePass
{
    public function __construct()
    {
        parent::__construct(AsIgnored::class);
    }

    public function register(ContainerBuilder $container, \ReflectionClass $reflectionClass, AsIgnored $attribute): void
    {
        if (!EnvironmentHelper::isPermittedEnvironment($container, $reflectionClass->getName())) {
            return;
        }

        match (true) {
            $container->hasDefinition($reflectionClass->getName()) => $container->removeDefinition($reflectionClass->getName()),
            $container->hasAlias($reflectionClass->getName()) => $container->removeAlias($reflectionClass->getName()),
            default => null,
        };
    }

    protected function accept(Definition $definition): bool
    {
        return $definition->isAutoconfigured() && $this->isAttributesIgnored($definition);
    }
}
