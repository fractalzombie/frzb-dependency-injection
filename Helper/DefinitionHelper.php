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

namespace FRZB\Component\DependencyInjection\Helper;

use Fp\Collections\ArrayList;
use Fp\Collections\Entry;
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

    private function __construct()
    {
    }

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
            : $container->getDefinition($serviceId)->getClass()
        ;
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
            ->map(fn (Entry $e) => self::mapDefinitionArguments($container, $e->value))
            ->toArray()
        ;
    }

    public static function mapDefinitionArguments(ContainerBuilder $container, array $arguments): array
    {
        $definitionsById = HashMap::collect($arguments)
            ->filter(static fn (Entry $e) => \is_string($e->value))
            ->filter(static fn (Entry $e) => str_contains($e->value, self::SERVICE_PREFIX))
            ->map(static fn (Entry $e) => str_replace(self::SERVICE_PREFIX, self::EMPTY_STRING, $e->value))
            ->filter(static fn (Entry $e) => $container->hasDefinition($e->value))
            ->map(static fn (Entry $e) => new Reference((string) $e->value))
            ->toAssocArray()
            ->getOrElse([])
        ;

        $definitionsByClass = HashMap::collect($arguments)
            ->filter(static fn (Entry $e) => \is_string($e->value))
            ->filter(static fn (Entry $e) => class_exists($e->value))
            ->filter(static fn (Entry $e) => $container->hasDefinition($e->value))
            ->map(static fn (Entry $e) => new Reference((string) $e->value))
            ->toAssocArray()
            ->getOrElse([])
        ;

        $definitionsByAlias = HashMap::collect($arguments)
            ->filter(static fn (Entry $e) => \is_string($e->value))
            ->filter(static fn (Entry $e) => interface_exists($e->value) || class_exists($e->value))
            ->filter(static fn (Entry $e) => $container->hasAlias($e->value))
            ->map(static fn (Entry $e) => new Reference((string) $e->value))
            ->toAssocArray()
            ->getOrElse([])
        ;

        return [...$definitionsById, ...$definitionsByClass, ...$definitionsByAlias];
    }
}
