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

use FRZB\Component\DependencyInjection\Attribute\AsDecorator;
use FRZB\Component\DependencyInjection\Attribute\AsService;
use JetBrains\PhpStorm\Pure;

/** @internal */
#[AsService]
#[AsDecorator(decorates: Service::class, innerName: '@.inner')]
class DecoratedService
{
    private Service $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    #[Pure]
    public function getService(): ServiceInterface
    {
        return $this->service;
    }
}
