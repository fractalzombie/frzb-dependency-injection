<?php

declare(strict_types=1);

namespace FRZB\Component\DependencyInjection\Helper;

use FRZB\Component\DependencyInjection\Attribute\AsTagged;
use JetBrains\PhpStorm\Immutable;

/** @internal */
#[Immutable]
final class TagHelper
{
    private function __construct()
    {
    }

    public static function toTagRepresentation(AsTagged $tag): array
    {
        return [$tag->{$tag::getNameProperty()} => [PropertyHelper::mapProperties($tag, [$tag::getNameProperty()])]];
    }

    public static function toTag(AsTagged $tag): array
    {
        return PropertyHelper::mapProperties($tag, [$tag::getNameProperty()]);
    }
}
