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

use Fp\Collections\ArrayList;
use Fp\Collections\Entry;
use Fp\Collections\HashMap;
use FRZB\Component\DependencyInjection\Attribute\AsService;
use FRZB\Component\DependencyInjection\Attribute\AsTagged;
use FRZB\Component\DependencyInjection\Helper\EnvironmentHelper;
use FRZB\Component\DependencyInjection\Helper\TagHelper;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Register #[AsService] attribute on definition that is autoconfigured.
 *
 * @author Mykhailo Shtanko <fractalzombie@gmail.com>
 */
final class RegisterAsServiceAttributePass extends AbstractRegisterAttributePass
{
    #[Pure]
    public function __construct()
    {
        parent::__construct(AsService::class);
    }

    protected function accept(Definition $definition): bool
    {
        return $definition->isAutoconfigured() && $this->isAttributesIgnored($definition);
    }

    protected function register(ContainerBuilder $container, \ReflectionClass $rClass, AsService $attribute): void
    {
        $definition = $container->getDefinition($rClass->getName());

        if (!EnvironmentHelper::isPermittedEnvironment($container, $rClass->getName())) {
            return;
        }

        $arguments = [
            ...$definition->getArguments(),
            ...$attribute->arguments,
            ...$this->getDefinitions($container, $attribute->arguments),
        ];

        $methodCalls = [
            ...$definition->getProperties(),
            ...$this->getMethodCalls($container, $attribute->calls),
        ];

        $properties = [...$definition->getProperties(), ...$attribute->properties];
        $bindings = [...$definition->getBindings(), ...$attribute->bindings];
        $tags = [...$definition->getTags(), ...$this->getTags(...$attribute->tags)];

        $definition
            ->setShared($attribute->isShared ?? $definition->isShared())
            ->setSynthetic($attribute->isSynthetic ?? $definition->isSynthetic())
            ->setLazy($attribute->isLazy ?? $definition->isLazy())
            ->setPublic($attribute->isPublic ?? $definition->isPublic())
            ->setAbstract($attribute->isAbstract ?? $definition->isAbstract())
            ->setFactory($attribute->factory ?? $definition->getFactory())
            ->setFile($attribute->file ?? $definition->getFile())
            ->setArguments($arguments)
            ->setProperties($properties)
            ->setConfigurator($attribute->configurator ?? $definition->getConfigurator())
            ->setMethodCalls($methodCalls)
            ->setTags($tags)
            ->setAutowired($attribute->isAutowired ?? $definition->isAutowired())
            ->setAutoconfigured($attribute->isAutoconfigured ?? $definition->isAutoconfigured())
            ->setBindings($bindings)
        ;
    }

    private function getTags(AsTagged ...$tags): array
    {
        return ArrayList::collect($tags)
            ->map(TagHelper::toTagRepresentation(...))
            ->reduce(array_merge(...))
            ->getOrElse([])
        ;
    }

    private function getMethodCalls(ContainerBuilder $container, array $methodCalls): array
    {
        return HashMap::collect($methodCalls)
            ->map(fn (Entry $e) => $this->getDefinitions($container, $e->value))
            ->toArray()
        ;
    }

    private function getDefinitions(ContainerBuilder $container, array $arguments): array
    {
        $definitionsById = HashMap::collect($arguments)
            ->filter(static fn (Entry $e) => \is_string($e->value))
            ->filter(static fn (Entry $e) => str_contains($e->value, '@'))
            ->map(static fn (Entry $e) => str_replace('@', '', $e->value))
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
