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

namespace FRZB\Component\DependencyInjection\Tests\Util\Helper;

use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\ServiceWithCorrectWhenAttribute;

/** @internal */
interface TestConstant
{
    public const TEST_ENVIRONMENT = 'TEST_VALUE';
    public const TEST_SERVICE_WITH_ARGUMENT_NAME = '$serviceWithArgument';
    public const TEST_SERVICE_WITH_ARGUMENT_FULL_NAME = 'FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\ServiceInterface '.self::TEST_SERVICE_WITH_ARGUMENT_NAME;
    public const TEST_SERVICE_WITHOUT_WHEN_ATTRIBUTE_BUT_WITH_CORRECT_AS_ALIAS_ENV = ServiceWithCorrectWhenAttribute::class.' $serviceWithoutWhenAttributeButWithCorrectAsAliasEnv';
    public const TEST_DEPRECATION_ATTRIBUTE_MESSAGE = [
        'package' => 'frzb/dependency-injection',
        'version' => '1.0.0',
        'message' => 'The "FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\DeprecatedService" service is deprecated. You should stop using it, as it will be removed in the future.',
    ];
}
