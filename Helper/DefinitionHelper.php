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

namespace FRZB\Component\DependencyInjection\Helper;

use Fp\Collections\ArrayList;
use Fp\Collections\HashMap;
use JetBrains\PhpStorm\Immutable;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Reference;

/** @internal */
#[Immutable]
final class DefinitionHelper
{
    private const SERVICE_PREFIX = '@';
    private const EMPTY_STRING = '';

    private function __construct() {}

    /**
     * @template T
     *
     * @param class-string<T> $attributeName
     *
     * @return T[]
     */
    public static function getAttributesFor(\ReflectionClass $reflectionClass, string $attributeName): array
    {
        return ArrayList::collect($reflectionClass->getAttributes($attributeName, \ReflectionAttribute::IS_INSTANCEOF))
            ->map(static fn (\ReflectionAttribute $reflectionAttribute) => $reflectionAttribute->newInstance())
            ->toArray()
        ;
    }

    public static function getServiceId(ContainerBuilder $container, \ReflectionClass $reflectionClass, ?string $serviceId): string
    {
        return match (true) {
            null !== $serviceId && $container->hasDefinition($serviceId) => $serviceId,
            null === $serviceId && $container->hasDefinition($reflectionClass->getName()) => $reflectionClass->getName(),
            default => throw new ServiceNotFoundException($attribute->id ?? $reflectionClass->getName()),
        };
    }

    public static function getClassForServiceId(ContainerBuilder $container, string $serviceId): string
    {
        return class_exists($serviceId)
            ? $serviceId
            : $container->getDefinition($serviceId)->getClass();
    }

    /** @throws \ReflectionException */
    public static function getReflectionClassForServiceId(ContainerBuilder $container, string $serviceId): \ReflectionClass
    {
        return $container->getReflectionClass(self::getClassForServiceId($container, $serviceId));
    }

    public static function getDefinitionForServiceId(ContainerBuilder $container, string $serviceId): Definition
    {
        return $container->getDefinition(self::getClassForServiceId($container, $serviceId));
    }

    public static function mapDefinitionMethodCalls(ContainerBuilder $container, array $methodCalls): array
    {
        return HashMap::collect($methodCalls)
            ->map(fn (array $value) => self::mapDefinitionArguments($container, $value))
            ->toArray()
        ;
    }

    public static function mapDefinitionArguments(ContainerBuilder $container, array $arguments): array
    {
        $definitionsById = HashMap::collect($arguments)
            ->filter(static fn (string $value) => \is_string($value))
            ->filter(static fn (string $value) => str_contains($value, self::SERVICE_PREFIX))
            ->map(static fn (string $value) => str_replace(self::SERVICE_PREFIX, self::EMPTY_STRING, $value))
            ->filter(static fn (string $value) => $container->hasDefinition($value))
            ->map(static fn (string $value) => new Reference($value))
            ->toArray()
        ;

        $definitionsByClass = HashMap::collect($arguments)
            ->filter(static fn (string $value) => \is_string($value))
            ->filter(static fn (string $value) => class_exists($value))
            ->filter(static fn (string $value) => $container->hasDefinition($value))
            ->map(static fn (string $value) => new Reference($value))
            ->toArray()
        ;

        $definitionsByAlias = HashMap::collect($arguments)
            ->filter(static fn (string $value) => \is_string($value))
            ->filter(static fn (string $value) => interface_exists($value) || class_exists($value))
            ->filter(static fn (string $value) => $container->hasAlias($value))
            ->map(static fn (string $value) => new Reference($value))
            ->toArray()
        ;

        return [...$definitionsById, ...$definitionsByClass, ...$definitionsByAlias];
    }
}
