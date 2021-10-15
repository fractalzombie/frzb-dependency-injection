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

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class AsAlias
{
    public const WITHOUT_ARGUMENT_NAME = 0;
    public const WITH_ARGUMENT_NAME = 1;
    public const LOGIC_EXCEPTION = 2;

    public function __construct(
        private string $service,
        private bool $public = true,
        private ?string $aliasForArgument = null,
    ) {
    }

    public function getService(): string
    {
        return $this->service;
    }

    public function isPublic(): bool
    {
        return $this->public;
    }

    public function getAliasForArgument(): ?string
    {
        return $this->aliasForArgument ? str_replace('$', '', $this->aliasForArgument) : null;
    }

    public function getAliasState(): int
    {
        return match (true) {
            !empty($this->getAliasForArgument()) => self::WITH_ARGUMENT_NAME,
            empty($this->getAliasForArgument()) => self::WITHOUT_ARGUMENT_NAME,
            default => self::LOGIC_EXCEPTION,
        };
    }
}
