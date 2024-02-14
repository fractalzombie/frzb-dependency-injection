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

namespace Enum;

use FRZB\Component\DependencyInjection\Enum\InvalidType;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/** @internal */
#[Group('dependency-injection')]
final class InvalidTypeTest extends TestCase
{
    public function testReferenceValues(): void
    {
        self::assertSame(ContainerInterface::RUNTIME_EXCEPTION_ON_INVALID_REFERENCE, InvalidType::RuntimeExceptionOnInvalidReference->value);
        self::assertSame(ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE, InvalidType::ExceptionOnInvalidReference->value);
        self::assertSame(ContainerInterface::NULL_ON_INVALID_REFERENCE, InvalidType::NullOnInvalidReference->value);
        self::assertSame(ContainerInterface::IGNORE_ON_INVALID_REFERENCE, InvalidType::IgnoreOnInvalidReference->value);
        self::assertSame(ContainerInterface::IGNORE_ON_UNINITIALIZED_REFERENCE, InvalidType::IgnoreOnUninitializedReference->value);
    }
}
