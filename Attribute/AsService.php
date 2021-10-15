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

namespace FRZB\Component\DependencyInjection\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class AsService
{
    public function __construct(
        private bool $shared = true,
        private bool $synthetic = false,
        private bool $lazy = true,
        private bool $public = true,
        private bool $abstract = false,
        private string|array|null $factory = null,
        private ?string $file = null,
        private array $arguments = [],
        private array $properties = [],
        private string|array|null $configurator = null,
        private array $calls = [],
        private array $tags = [],
        private bool $autowire = true,
        private bool $autoconfigured = true,
        private array $bindings = [],
    ) {
    }

    public function isShared(): bool
    {
        return $this->shared;
    }

    public function isSynthetic(): bool
    {
        return $this->synthetic;
    }

    public function isLazy(): bool
    {
        return $this->lazy;
    }

    public function isPublic(): bool
    {
        return $this->public;
    }

    public function isAbstract(): bool
    {
        return $this->abstract;
    }

    public function getFactory(): array|string|null
    {
        return $this->factory;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function getArguments(): array
    {
        return array_combine(
            array_map(static fn (string $key) => str_contains($key, '$') ? $key : '$'.$key, array_keys($this->arguments)),
            array_values($this->arguments)
        );
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

    public function getConfigurator(): array|string|null
    {
        return $this->configurator;
    }

    public function getCalls(): array
    {
        return $this->calls;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function isAutowire(): bool
    {
        return $this->autowire;
    }

    public function isAutoconfigured(): bool
    {
        return $this->autoconfigured;
    }

    public function getBindings(): array
    {
        return $this->bindings;
    }
}
