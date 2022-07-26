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

use Fp\Collections\ArrayList;
use Symfony\Component\DependencyInjection\Attribute\When;

abstract class AbstractAttributeRegister implements AttributeRegisterInterface
{
    /** {@inheritdoc} */
    public static function getAttribute(string $attributeClass, \ReflectionAttribute $rAttribute): object
    {
        return $rAttribute->newInstance();
    }

    protected static function isPermittedEnvironmentOrEnvironmentIsNotDefined(string $currentEnvironment, string $serviceClass): bool
    {
        try {
            $rService = new \ReflectionClass($serviceClass);
        } catch (\ReflectionException) {
            return false;
        }

        $permittedEnvironments = ArrayList::collect($rService->getAttributes(When::class))
            ->map(static fn (\ReflectionAttribute $attribute) => self::getAttribute(When::class, $attribute)->env)
        ;

        $isEnvironmentIsNotDefined = $permittedEnvironments->isEmpty();
        $isEnvironmentPermitted = $permittedEnvironments
            ->first(static fn (string $environment) => $environment === $currentEnvironment)
            ->isNonEmpty()
        ;

        return $isEnvironmentPermitted || $isEnvironmentIsNotDefined;
    }
}
