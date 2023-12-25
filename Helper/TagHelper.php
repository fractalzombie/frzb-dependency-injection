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

namespace FRZB\Component\DependencyInjection\Helper;

use Fp\Collections\ArrayList;
use FRZB\Component\DependencyInjection\Attribute\AsTagged;
use JetBrains\PhpStorm\Immutable;

/** @internal */
#[Immutable]
final class TagHelper
{
    private function __construct() {}

    public static function toTagRepresentation(AsTagged $tag): array
    {
        return [$tag->{$tag::getNameProperty()} => [PropertyHelper::mapProperties($tag, [$tag::getNameProperty()])]];
    }

    public static function toTag(AsTagged $tag): array
    {
        return PropertyHelper::mapProperties($tag, [$tag::getNameProperty()]);
    }

    public static function mapTags(AsTagged ...$tags): array
    {
        return ArrayList::collect($tags)
            ->map(self::toTagRepresentation(...))
            ->toMergedArray()
        ;
    }
}
