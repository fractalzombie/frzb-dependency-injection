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

    protected function accept(Definition $definition): bool
    {
        return $definition->isAutoconfigured() && $this->isAttributesIgnored($definition);
    }

    protected function register(ContainerBuilder $container, \ReflectionClass $reflectionClass, AsService $attribute): void
    {
        if (!EnvironmentHelper::isPermittedEnvironment($container, $reflectionClass->getName())) {
            return;
        }
        
        $definition = $container->getDefinition($reflectionClass->getName());

        $arguments = [
            ...$definition->getArguments(),
            ...$attribute->arguments,
            ...DefinitionHelper::mapDefinitionArguments($container, $attribute->arguments),
        ];

        $methodCalls = [
            ...$definition->getMethodCalls(),
            ...DefinitionHelper::mapDefinitionMethodCalls($container, $attribute->calls),
        ];

        $properties = [
            ...$definition->getProperties(),
            ...$attribute->properties,
        ];

        $bindings = [
            ...$definition->getBindings(),
            ...$attribute->bindings,
        ];

        $tags = [
            ...$definition->getTags(),
            ...TagHelper::mapTags(...$attribute->tags),
        ];

        $factory = $attribute->factory ?? $definition->getFactory();
        $configurator = $attribute->configurator ?? $definition->getConfigurator();
        $file = $attribute->file ?? $definition->getFile();

        $isShared = $attribute->isShared ?? $definition->isShared();
        $isLazy = $attribute->isLazy ? !$reflectionClass->isFinal() : $attribute->isLazy;
        $isAbstract = $attribute->isAbstract ? $reflectionClass->isAbstract() : $attribute->isAbstract;
        $isPublic = $attribute->isPublic ?? $definition->isPublic();
        $isSynthetic = $attribute->isSynthetic ?? $definition->isSynthetic();
        $isAutowired = $attribute->isAutowired ?? $definition->isAutowired();
        $isAutoconfigured = $attribute->isAutoconfigured ?? $definition->isAutoconfigured();

        $definition
            ->setFactory($factory)
            ->setFile($file)
            ->setArguments($arguments)
            ->setProperties($properties)
            ->setConfigurator($configurator)
            ->setMethodCalls($methodCalls)
            ->setTags($tags)
            ->setBindings($bindings)
            ->setShared($isShared)
            ->setSynthetic($isSynthetic)
            ->setLazy($isLazy)
            ->setPublic($isPublic)
            ->setAbstract($isAbstract)
            ->setAutowired($isAutowired)
            ->setAutoconfigured($isAutoconfigured)
        ;
    }
}
