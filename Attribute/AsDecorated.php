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

use Symfony\Component\DependencyInjection\ContainerInterface;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class AsDecorated
{
    public function __construct(
        private ?string $decorates,
        private ?string $decorationInnerName,
        private ?int $decorationPriority = 0,
        private ?int $decorationOnInvalid = ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE,
    ) {
    }

    public function getDecorates(): ?string
    {
        return $this->decorates;
    }

    public function getDecorationInnerName(): ?string
    {
        return $this->decorationInnerName;
    }

    public function getDecorationPriority(): ?int
    {
        return $this->decorationPriority;
    }

    public function getDecorationOnInvalid(): ?int
    {
        return $this->decorationOnInvalid;
    }
}
