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

namespace FRZB\Component\DependencyInjection\Enum;

enum InvalidType: int
{
    case RuntimeExceptionOnInvalidReference = 0;
    case ExceptionOnInvalidReference = 1;
    case NullOnInvalidReference = 2;
    case IgnoreOnInvalidReference = 3;
    case IgnoreOnUninitializedReference = 4;
}
