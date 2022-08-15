<?php

declare(strict_types=1);

namespace FRZB\Component\DependencyInjection\Helper;

use Fp\Collections\Entry;
use Fp\Collections\HashMap;
use JetBrains\PhpStorm\Immutable;
use Symfony\Component\DependencyInjection\ContainerBuilder;
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
