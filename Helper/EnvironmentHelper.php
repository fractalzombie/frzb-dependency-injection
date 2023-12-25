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

namespace FRZB\Component\DependencyInjection\Helper;

use Fp\Collections\ArrayList;
use JetBrains\PhpStorm\Immutable;
use Symfony\Component\DependencyInjection\Attribute\When;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/** @internal */
#[Immutable]
final class EnvironmentHelper
{
    private function __construct() {}

    public static function isPermittedEnvironment(ContainerBuilder $container, \ReflectionClass $reflectionClass): bool
    {
        $currentEnvironment = $container->getParameter('kernel.environment');
        $permittedEnvironments = ArrayList::collect($reflectionClass->getAttributes(When::class))
            ->map(static fn (\ReflectionAttribute $attribute) => $attribute->newInstance()->env)
        ;

        $isEnvironmentIsNotDefined = $permittedEnvironments->isEmpty();
        $isEnvironmentPermitted = $permittedEnvironments
            ->first(static fn (string $environment) => $environment === $currentEnvironment)
            ->isSome()
        ;

        return $isEnvironmentPermitted || $isEnvironmentIsNotDefined;
    }
}
