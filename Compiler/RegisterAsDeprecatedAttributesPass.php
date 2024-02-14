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
use FRZB\Component\DependencyInjection\Attribute\AsDeprecated;
use FRZB\Component\DependencyInjection\Attribute\AsService;
use FRZB\Component\DependencyInjection\Helper\DefinitionHelper;
use FRZB\Component\DependencyInjection\Helper\EnvironmentHelper;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @internal
 *
 * Register #[AsDeprecated] attribute on definition that is autoconfigured
 *
 * @author Mykhailo Shtanko <fractalzombie@gmail.com>
 */
final class RegisterAsDeprecatedAttributesPass extends AbstractRegisterAttributePass
{
    public function __construct()
    {
        parent::__construct(AsDeprecated::class);
    }

    public function register(ContainerBuilder $container, \ReflectionClass $reflectionClass, AsDeprecated $attribute): void
    {
        if (!EnvironmentHelper::isPermittedEnvironment($container, $reflectionClass)) {
            return;
        }

        ArrayList::collect($reflectionClass->getAttributes(AsService::class, \ReflectionAttribute::IS_INSTANCEOF))
            ->map(static fn (\ReflectionAttribute $reflectionAttribute) => $reflectionAttribute->newInstance())
            ->map(static fn (AsService $asService) => $container->getDefinition(DefinitionHelper::getServiceId($container, $reflectionClass, $asService->id)))
            ->appended($container->getDefinition($reflectionClass->getName()))
            ->tap(static fn (Definition $definition) => $definition->setDeprecated($attribute->package, $attribute->version, $attribute->message))
        ;
    }
}
