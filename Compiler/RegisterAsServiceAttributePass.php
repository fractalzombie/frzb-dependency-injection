<?php

declare(strict_types=1);

/**
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 *
 * Copyright (c) 2023 Mykhailo Shtanko fractalzombie@gmail.com
 *
 * For the full copyright and license information, please view the LICENSE.MD
 * file that was distributed with this source code.
 */

namespace FRZB\Component\DependencyInjection\Compiler;

use FRZB\Component\DependencyInjection\Attribute\AsService;
use FRZB\Component\DependencyInjection\Helper\DefinitionHelper;
use FRZB\Component\DependencyInjection\Helper\EnvironmentHelper;
use FRZB\Component\DependencyInjection\Helper\TagHelper;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @internal
 *
 * Register #[AsService] attribute on definition that is autoconfigured
 *
 * @author Mykhailo Shtanko <fractalzombie@gmail.com>
 */
final class RegisterAsServiceAttributePass extends AbstractRegisterAttributePass
{
    public function __construct()
    {
        parent::__construct(AsService::class);
    }

    protected function register(ContainerBuilder $container, \ReflectionClass $reflectionClass, AsService $attribute): void
    {
        if (!EnvironmentHelper::isPermittedEnvironment($container, $reflectionClass)) {
            return;
        }

        $definition = ($container->hasDefinition($id = $attribute->id ?? $reflectionClass->getName()))
            ? $container->getDefinition($id)->setClass($reflectionClass->getName())
            : $container->setDefinition($id, new Definition())->setClass($reflectionClass->getName());

        foreach (self::mapArguments($container, $definition, $reflectionClass, $attribute) as $method => $arguments) {
            $definition->{$method}($arguments);
        }
    }

    private static function mapArguments(
        ContainerBuilder $container,
        Definition $definition,
        \ReflectionClass $reflectionClass,
        AsService $attribute
    ): array {
        return [
            'setFile' => $attribute->file ?? $definition->getFile(),
            'setShared' => $attribute->isShared ?? $definition->isShared(),
            'setPublic' => $attribute->isPublic ?? $definition->isPublic(),
            'setFactory' => $attribute->factory ?? $definition->getFactory(),
            'setAutowired' => $attribute->isAutowired ?? $definition->isAutowired(),
            'setSynthetic' => $attribute->isSynthetic ?? $definition->isSynthetic(),
            'setBindings' => [...$definition->getBindings(), ...$attribute->bindings],
            'setConfigurator' => $attribute->configurator ?? $definition->getConfigurator(),
            'setProperties' => [...$definition->getProperties(), ...$attribute->properties],
            'setLazy' => $attribute->isLazy ? !$reflectionClass->isFinal() : $attribute->isLazy,
            'setTags' => [...$definition->getTags(), ...TagHelper::mapTags(...$attribute->tags)],
            'setAutoconfigured' => $attribute->isAutoconfigured ?? $definition->isAutoconfigured(),
            'setAbstract' => $attribute->isAbstract ? $reflectionClass->isAbstract() : $attribute->isAbstract,
            'setArguments' => [
                ...$definition->getArguments(),
                ...$attribute->arguments,
                ...DefinitionHelper::mapDefinitionArguments($container, $attribute->arguments),
            ],
            'setMethodCalls' => [
                ...$definition->getMethodCalls(),
                ...DefinitionHelper::mapDefinitionMethodCalls($container, $attribute->calls),
            ],
        ];
    }
}
