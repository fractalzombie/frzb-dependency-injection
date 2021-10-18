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

abstract class AbstractAttributeRegister implements AttributeRegisterInterface
{
    public static function getAttribute(string $attributeClass, \ReflectionAttribute $rAttribute): object
    {
        return $rAttribute->newInstance();
    }
}
