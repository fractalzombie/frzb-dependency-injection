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

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @template T
 */
interface AttributeRegisterInterface
{
    /**
     * Register dependency in container by attribute.
     */
    public function register(ContainerBuilder $container, \ReflectionClass $rClass, \ReflectionAttribute $rAttribute): void;

    /**
     * Get attribute instance.
     *
     * @param class-string<T> $attributeClass
     *
     * @return T
     */
    public static function getAttribute(string $attributeClass, \ReflectionAttribute $rAttribute): object;
}
