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

namespace FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service;

use FRZB\Component\DependencyInjection\Attribute\AsService;
use FRZB\Component\DependencyInjection\Attribute\AsTagged;

/** @internal */
#[AsService(id: 'named_service.first', arguments: ['$type' => 'first']), AsTagged(NamedServiceInterface::class, 'named_service.first')]
#[AsService(id: 'named_service.second', arguments: ['$type' => 'second']), AsTagged(NamedServiceInterface::class, 'named_service.second')]
class NamedService implements NamedServiceInterface
{
    public function __construct(
        private readonly string $type,
    ) {}

    public function getType(): string
    {
        return $this->type;
    }
}
