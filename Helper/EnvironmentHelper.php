<?php

declare(strict_types=1);


namespace FRZB\Component\DependencyInjection\Helper;

use Fp\Collections\ArrayList;
use JetBrains\PhpStorm\Immutable;
use Symfony\Component\DependencyInjection\Attribute\When;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/** @internal */
#[Immutable]
final class EnvironmentHelper
{
    private function __construct()
    {
    }

    public static function isPermittedEnvironment(ContainerBuilder $container, string $serviceClass): bool
    {
        try {
            $reflectionClass = new \ReflectionClass($serviceClass);
            $currentEnvironment = $container->getParameter('kernel.environment');
        } catch (\ReflectionException) {
            return false;
        }

        $permittedEnvironments = ArrayList::collect($reflectionClass->getAttributes(When::class))
            ->map(static fn (\ReflectionAttribute $attribute) => $attribute->newInstance()->env)
        ;

        $isEnvironmentIsNotDefined = $permittedEnvironments->isEmpty();
        $isEnvironmentPermitted = $permittedEnvironments
            ->first(static fn (string $environment) => $environment === $currentEnvironment)
            ->isNonEmpty()
        ;

        return $isEnvironmentPermitted || $isEnvironmentIsNotDefined;
    }
}
