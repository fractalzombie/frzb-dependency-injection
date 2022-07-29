<?php

declare(strict_types=1);

namespace FRZB\Component\DependencyInjection\Tests\Util\Hook;

use DG\BypassFinals;
use PHPUnit\Runner\BeforeTestHook;

/** @internal */
final class BypassFinalHook implements BeforeTestHook
{
    public function executeBeforeTest(string $test): void
    {
        BypassFinals::enable();
    }
}
