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

namespace FRZB\Component\DependencyInjection\Attribute;

use FRZB\Component\DependencyInjection\Enum\InvalidType;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
#[\Attribute(\Attribute::TARGET_CLASS)]
final class AsDecorator
{
    public function __construct(
        public readonly string $decorates,
        public readonly ?string $innerName = null,
        public readonly int $priority = 0,
        public readonly InvalidType $onInvalid = InvalidType::ExceptionOnInvalidReference,
    ) {}
}
