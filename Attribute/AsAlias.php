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

namespace FRZB\Component\DependencyInjection\Attribute;

use FRZB\Component\DependencyInjection\Enum\AliasType;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class AsAlias
{
    public readonly AliasType $aliasType;

    public function __construct(
        public readonly string $service,
        public readonly bool $isPublic = true,
        public readonly ?string $aliasForArgument = null,
    ) {
        $this->aliasType = match (true) {
            !empty($this->aliasForArgument) => AliasType::WithArgumentName,
            empty($this->aliasForArgument) => AliasType::WithoutArgumentName,
            default => AliasType::LogicException,
        };
    }

    public function getServiceAlias(): string
    {
        return match ($this->aliasType) {
            AliasType::WithArgumentName => sprintf('%s %s', $this->service, $this->aliasForArgument),
            AliasType::WithoutArgumentName, AliasType::LogicException => $this->service,
        };
    }
}
