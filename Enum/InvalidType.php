<?php

declare(strict_types=1);

namespace FRZB\Component\DependencyInjection\Enum;

enum InvalidType: int
{
    case RuntimeExceptionOnInvalidReference = 0;
    case ExceptionOnInvalidReference = 1;
    case NullOnInvalidReference = 2;
    case IgnoreOnInvalidReference = 3;
    case IgnoreOnUninitializedReference = 4;
}
