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

use Fp\Collections\ArrayList;
use Fp\Collections\Entry;
use Fp\Collections\HashMap;
use FRZB\Component\DependencyInjection\Exception\AttributeException;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
#[\Attribute(\Attribute::TARGET_CLASS)]
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
        public readonly string|array|null $factory = null,
        public readonly ?string $file = null,
        array $arguments = [],
        public readonly array $properties = [],
        public readonly string|array|null $configurator = null,
        public readonly array $calls = [],
        /** @var AsTagged[] */
        public readonly array $tags = [],
        public readonly bool $isAutowired = true,
        public readonly bool $isAutoconfigured = true,
        public readonly array $bindings = [],
    ) {
        $this->arguments = HashMap::collect($arguments)
            ->mapKeys(static fn (Entry $argument) => match (true) {
                str_contains($argument->key, self::PARAMETER_PREFIX) => $argument->key,
                default => self::PARAMETER_PREFIX.$argument->key,
            })
            ->toAssocArray()
            ->getOrElse([])
        ;

        ArrayList::collect($this->tags)
            ->filter(static fn (mixed $tag) => !$tag instanceof AsTagged)
            ->tap(static fn (mixed $tag) => throw AttributeException::mustBeOfType(AsTagged::class, $tag))
        ;
    }
}
