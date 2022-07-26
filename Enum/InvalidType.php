<?php

declare(strict_types=1);

namespace FRZB\Component\DependencyInjection\Enum;

use Symfony\Component\DependencyInjection\ContainerInterface;

enum InvalidType: int
{
    case RuntimeExceptionOnInvalidReference = ContainerInterface::RUNTIME_EXCEPTION_ON_INVALID_REFERENCE;
    case ExceptionOnInvalidReference = ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE;
    case NullOnInvalidReference = ContainerInterface::NULL_ON_INVALID_REFERENCE;
    case IgnoreOnInvalidReference = ContainerInterface::IGNORE_ON_INVALID_REFERENCE;
    case IgnoreOnUninitializedReference = ContainerInterface::IGNORE_ON_UNINITIALIZED_REFERENCE;
}
