<?php

declare(strict_types=1);

namespace FRZB\Component\DependencyInjection\Tests\Util\Helper;

interface TestConstant
{
    public const TEST_ENVIRONMENT = 'TEST_VALUE';
    public const TEST_SERVICE_WITH_ARGUMENT_NAME = '$serviceWithArgument';
    public const TEST_SERVICE_WITH_ARGUMENT_FULL_NAME = 'FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\ServiceInterface '.self::TEST_SERVICE_WITH_ARGUMENT_NAME;
    public const TEST_DEPRECATION_ATTRIBUTE_MESSAGE = [
        'package' => 'frzb/dependency-injection',
        'version' => '1.0.0',
        'message' => 'The "FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\DeprecatedService" service is deprecated. You should stop using it, as it will be removed in the future.',
    ];
}
