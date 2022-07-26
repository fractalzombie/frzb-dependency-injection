<?php

declare(strict_types=1);

namespace FRZB\Component\DependencyInjection\Enum;

enum AliasType: int
{
    case WithoutArgumentName = 0;
    case WithArgumentName = 1;
    case LogicException = 2;
}
