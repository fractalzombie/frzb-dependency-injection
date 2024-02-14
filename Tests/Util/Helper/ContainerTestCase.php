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

namespace FRZB\Component\DependencyInjection\Tests\Util\Helper;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Alias;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/** @internal */
abstract class ContainerTestCase extends TestCase
{
    private const CONFIG_FILE_PATH = __DIR__.'/../../Resources/config';
    private const CONFIG_FILE_NAME = 'services.php';
    private ContainerBuilder $container;
    private LoaderInterface $loader;

    public function getLoader(): LoaderInterface
    {
        return $this->loader;
    }

    /**
     * @template T
     *
     * @param class-string<T> $class
     *
     * @return null|T
     */
    public function get(string $class): ?object
    {
        try {
            return $this->container->get($class);
        } catch (\Throwable) {
            return null;
        }
    }

    protected function getContainer(): ContainerBuilder
    {
        return $this->container;
    }

    protected function loadServices(?ParameterBagInterface $parameterBag = null): void
    {
        try {
            $this->container = new ContainerBuilder($parameterBag);
            $this->container->setParameter('kernel.environment', $_SERVER['APP_ENV']);
            $this->loader = new PhpFileLoader($this->container, new FileLocator(self::CONFIG_FILE_PATH));
            $this->loader->load(self::CONFIG_FILE_NAME);
        } catch (\Throwable $e) {
            throw new \LogicException($e->getMessage(), $e->getCode(), $e);
        }
    }

    protected function addCompilerPasses(CompilerPassInterface ...$compilerPasses): void
    {
        foreach ($compilerPasses as $compilerPass) {
            $this->container->addCompilerPass($compilerPass);
        }
    }

    protected function compileContainer(bool $resolveEnvPlaceholders = true): void
    {
        $this->container->compile($resolveEnvPlaceholders);
    }

    protected function hasDefinition(string $definitionId): bool
    {
        return $this->container->hasDefinition($definitionId);
    }

    protected function hasAlias(string $aliasId): bool
    {
        return $this->container->hasAlias($aliasId);
    }

    protected function hasParameter(string $parameterName): bool
    {
        return $this->container->hasParameter($parameterName);
    }

    protected function getDefinition(string $definitionId): Definition
    {
        return $this->container->getDefinition($definitionId);
    }

    protected function getAlias(string $aliasId): Alias
    {
        return $this->container->getAlias($aliasId);
    }

    /** @return array<string, array> */
    protected function getTags(?string $name = null): array
    {
        return $name ? $this->container->findTaggedServiceIds($name) : $this->container->findTags();
    }

    /** @return Definition[] */
    protected function getDefinitions(): array
    {
        return $this->container->getDefinitions();
    }

    /** @return Alias[] */
    protected function getAliases(): array
    {
        return $this->container->getAliases();
    }
}
