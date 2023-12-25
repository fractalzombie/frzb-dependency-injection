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

use Fp\Collections\ArrayList;
use Fp\Collections\HashMap;
use FRZB\Component\DependencyInjection\Exception\AttributeException;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class AsService
{
    private const PARAMETER_PREFIX = '$';

    public readonly array $arguments;

    public function __construct(
        public readonly bool $isShared = true,
        public readonly bool $isSynthetic = false,
        public readonly bool $isLazy = true,
        public readonly bool $isPublic = true,
        public readonly bool $isAbstract = false,
        public readonly ?string $id = null,
        public readonly null|array|string $factory = null,
        public readonly ?string $file = null,
        array $arguments = [],
        public readonly array $properties = [],
        public readonly null|array|string $configurator = null,
        public readonly array $calls = [],
        /** @var AsTagged[] */
        public readonly array $tags = [],
        public readonly bool $isAutowired = true,
        public readonly bool $isAutoconfigured = true,
        public readonly array $bindings = [],
    ) {
        $this->arguments = HashMap::collect($arguments)
            ->mapKV(static fn (string $key, string $value) => match (true) {
                str_contains($key, self::PARAMETER_PREFIX) => [$key => $value],
                default => [self::PARAMETER_PREFIX.$key => $value],
            })->toMergedArray()
        ;

        ArrayList::collect($this->tags)
            ->filter(static fn (mixed $tag) => !$tag instanceof AsTagged)
            ->tap(static fn (mixed $tag) => throw AttributeException::mustBeOfType(AsTagged::class, $tag))
        ;
    }
}
