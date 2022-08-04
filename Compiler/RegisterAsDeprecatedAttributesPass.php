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

namespace FRZB\Component\DependencyInjection\Compiler;

use FRZB\Component\DependencyInjection\Attribute\AsDeprecated;
use FRZB\Component\DependencyInjection\Helper\EnvironmentHelper;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @internal
 *
 * Register #[AsDeprecated] attribute on definition that is autoconfigured
 *
 * @author Mykhailo Shtanko <fractalzombie@gmail.com>
 */
final class RegisterAsDeprecatedAttributesPass extends AbstractRegisterAttributePass
{
    public function __construct()
    {
        parent::__construct(AsDeprecated::class);
    }

    public function register(ContainerBuilder $container, \ReflectionClass $reflectionClass, AsDeprecated $attribute): void
    {
        if (!EnvironmentHelper::isPermittedEnvironment($container, $reflectionClass->getName())) {
            return;
        }

        $container->getDefinition($reflectionClass->getName())
            ->setDeprecated($attribute->package, $attribute->version, $attribute->message)
        ;
    }
}
