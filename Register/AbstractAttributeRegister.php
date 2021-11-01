<?php

declare(strict_types=1);

/*
 * This is package for Symfony framework.
 *
 * (c) Mykhailo Shtanko <fractalzombie@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FRZB\Component\DependencyInjection\Register;

use Symfony\Component\DependencyInjection\Attribute\When;

abstract class AbstractAttributeRegister implements AttributeRegisterInterface
{
    public static function getAttribute(string $attributeClass, \ReflectionAttribute $rAttribute): object
    {
        return $rAttribute->newInstance();
    }

    protected static function isPermittedEnvironmentOrEnvironmentIsNotDefined(string $environment, string $serviceClass): bool
    {
        try {
            $rService = new \ReflectionClass($serviceClass);
        } catch (\ReflectionException) {
            return false;
        }

        $permittedEnvironments = array_map(
            static fn (\ReflectionAttribute $a) => self::getAttribute(When::class, $a)->env,
            $rService->getAttributes(When::class),
        );

        $isEnvironmentPermitted = $permittedEnvironments && \in_array($environment, $permittedEnvironments, true);
        $isEnvironmentIsNotDefined = !$permittedEnvironments;

        return $isEnvironmentPermitted || $isEnvironmentIsNotDefined;
    }
}
