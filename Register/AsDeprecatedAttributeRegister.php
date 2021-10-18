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

use FRZB\Component\DependencyInjection\Attribute\AsDeprecated;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Register #[AsDeprecated] attribute on definition that is autoconfigured.
 *
 * @author Mykhailo Shtanko <fractalzombie@gmail.com>
 */
class AsDeprecatedAttributeRegister extends AbstractAttributeRegister
{
    public function register(ContainerBuilder $container, \ReflectionClass $rClass, \ReflectionAttribute $rAttribute): void
    {
        $attribute = self::getAttribute(AsDeprecated::class, $rAttribute);
        $definition = $container->getDefinition($rClass->getName());

        $container->setDefinition(
            $rClass->getName(),
            $definition->setDeprecated($attribute->getPackage(), $attribute->getVersion(), $attribute->getMessage())
        );
    }
}
