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

namespace FRZB\Component\DependencyInjection\Attribute\Register;

use FRZB\Component\DependencyInjection\Attribute\AsDecorated;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Register #[AsDecorated] attribute on definition that is autoconfigured.
 *
 * @author Mykhailo Shtanko <fractalzombie@gmail.com>
 */
class AsDecoratedAttributeRegister extends AbstractAttributeRegister
{
    public function register(ContainerBuilder $container, \ReflectionClass $rClass, \ReflectionAttribute $rAttribute): void
    {
        $attribute = self::getAttribute(AsDecorated::class, $rAttribute);
        $definition = $container->getDefinition($rClass->getName());

        $definition->setDecoratedService(
            $attribute->getDecorates(),
            $attribute->getDecorationInnerName(),
            $attribute->getDecorationPriority(),
            $attribute->getDecorationOnInvalid(),
        );

        $container->setDefinition($definition->getClass(), $definition);
    }
}
