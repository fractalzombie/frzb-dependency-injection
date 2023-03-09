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

use JetBrains\PhpStorm\Immutable;

#[Immutable]
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class AsTagged
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $id = null,
        public readonly ?string $alias = null,
        public readonly ?string $key = null,
        public readonly ?int $priority = null,
        public readonly array $attributes = [],
    ) {
    }

    public static function getNameProperty(): string
    {
        return 'name';
    }
}
