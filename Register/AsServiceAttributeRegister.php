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

use Fp\Collections\ArrayList;
use Fp\Collections\Entry;
use Fp\Collections\HashMap;
use FRZB\Component\DependencyInjection\Attribute\AsService;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Register #[AsService] attribute on definition that is autoconfigured.
 *
 * @author Mykhailo Shtanko <fractalzombie@gmail.com>
 */
class AsServiceAttributeRegister extends AbstractAttributeRegister
{
    public function register(ContainerBuilder $container, \ReflectionClass $rClass, \ReflectionAttribute $rAttribute): void
    {
        $environment = $container->getParameter('kernel.environment');
        $attribute = self::getAttribute(AsService::class, $rAttribute);
        $definition = $container->getDefinition($rClass->getName());

        if ($attribute && !self::isPermittedEnvironmentOrEnvironmentIsNotDefined($environment, $rClass->getName())) {
            return;
        }

        $arguments = [
            ...$definition->getArguments(),
            ...$attribute->getArguments(),
            ...$this->getDefinitions($container, $attribute->getArguments()),
        ];

        $methodCalls = [
            ...$definition->getProperties(),
            ...$this->getMethodCalls($container, $attribute->getCalls()),
        ];

        $definition
            ->setClass($definition->getClass())
            ->setShared($attribute->isShared() ?? $definition->isShared())
            ->setSynthetic($attribute->isSynthetic() ?? $definition->isSynthetic())
            ->setLazy($attribute->isLazy() ?? $definition->isLazy())
            ->setPublic($attribute->isPublic() ?? $definition->isPublic())
            ->setAbstract($attribute->isAbstract() ?? $definition->isAbstract())
            ->setFactory($attribute->getFactory() ?? $definition->getFactory())
            ->setFile($attribute->getFile() ?? $definition->getFile())
            ->setArguments($arguments)
            ->setProperties([...$definition->getProperties(), ...$attribute->getProperties()])
            ->setConfigurator($attribute->getConfigurator() ?? $definition->getConfigurator())
            ->setMethodCalls($methodCalls)
            ->setTags([...$definition->getTags(), ...$attribute->getTags()])
            ->setAutowired($attribute->isAutowire() ?? $definition->isAutowired())
            ->setAutoconfigured($attribute->isAutoconfigured() ?? $definition->isAutoconfigured())
            ->setBindings([...$definition->getBindings(), ...$attribute->getBindings()])
        ;

        $container->setDefinition($definition->getClass(), $definition);
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
