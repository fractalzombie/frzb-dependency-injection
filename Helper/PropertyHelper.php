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

namespace FRZB\Component\DependencyInjection\Helper;

use Fp\Collections\ArrayList;
use JetBrains\PhpStorm\Immutable;

/** @internal */
#[Immutable]
final class PropertyHelper
{
    private function __construct() {}

    public static function mapProperties(object $target, array $excludeNames = [], bool $excludeEmpty = true): array
    {
        try {
            $rClass = new \ReflectionClass($target);
        } catch (\Throwable) {
            return [];
        }

        return ArrayList::collect($rClass->getProperties())
            ->filter(
                static fn (\ReflectionProperty $property) => $excludeEmpty
                    ? !empty($property->getValue($target))
                    : null !== $property->getValue($target)
            )
            ->filter(static fn (\ReflectionProperty $property) => !\in_array($property->getName(), $excludeNames, true))
            ->map(static fn (\ReflectionProperty $property) => match (true) {
                \is_array($property->getValue($target)) => $property->getValue($target),
                default => [$property->getName() => $property->getValue($target)],
            })
            ->toMergedArray()
        ;
    }
}
