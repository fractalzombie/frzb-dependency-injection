<?php

declare(strict_types=1);

/**
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 *
 * Copyright (c) 2024 Mykhailo Shtanko fractalzombie@gmail.com
 *
 * For the full copyright and license information, please view the LICENSE.MD
 * file that was distributed with this source code.
 */

namespace FRZB\Component\DependencyInjection\Compiler;

use Fp\Collections\ArrayList;
use FRZB\Component\DependencyInjection\Attribute\AsAlias;
use FRZB\Component\DependencyInjection\Attribute\AsIgnored;
use FRZB\Component\DependencyInjection\Attribute\AsService;
use FRZB\Component\DependencyInjection\Helper\DefinitionHelper;
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
        if (!EnvironmentHelper::isPermittedEnvironment($container, $reflectionClass)) {
            return;
        }

        ArrayList::collect(DefinitionHelper::getAttributesFor($reflectionClass, AsService::class))
            ->map(static fn (\ReflectionAttribute $reflectionAttribute) => $reflectionAttribute->newInstance())
            ->filter(static fn (AsService $asService) => $container->hasDefinition(DefinitionHelper::getServiceId($container, $reflectionClass, $asService->id)))
            ->map(static fn (AsService $asService) => DefinitionHelper::getServiceId($container, $reflectionClass, $asService->id))
            ->tap(static fn (string $serviceId) => $container->removeDefinition($serviceId))
        ;

        ArrayList::collect(DefinitionHelper::getAttributesFor($reflectionClass, AsAlias::class))
            ->map(static fn (\ReflectionAttribute $reflectionAttribute) => $reflectionAttribute->newInstance())
            ->filter(static fn (AsAlias $asAlias) => $container->hasAlias(DefinitionHelper::getServiceId($container, $reflectionClass, $asAlias->getServiceAlias())))
            ->map(static fn (AsAlias $asAlias) => DefinitionHelper::getServiceId($container, $reflectionClass, $asAlias->service))
            ->tap(static fn (string $serviceId) => $container->removeAlias($serviceId))
        ;
    }

    protected function accept(Definition $definition): bool
    {
        return $definition->isAutoconfigured() && $this->isAttributesIgnored($definition);
    }
}
