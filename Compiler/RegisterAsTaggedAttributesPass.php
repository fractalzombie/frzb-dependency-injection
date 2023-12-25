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

namespace FRZB\Component\DependencyInjection\Compiler;

use FRZB\Component\DependencyInjection\Attribute\AsTagged;
use FRZB\Component\DependencyInjection\Helper\DefinitionHelper;
use FRZB\Component\DependencyInjection\Helper\EnvironmentHelper;
use FRZB\Component\DependencyInjection\Helper\TagHelper;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @internal
 *
 * Register #[AsTagged] attribute on definition that is autoconfigured
 *
 * @author Mykhailo Shtanko <fractalzombie@gmail.com>
 */
final class RegisterAsTaggedAttributesPass extends AbstractRegisterAttributePass
{
    public function __construct()
    {
        parent::__construct(AsTagged::class);
    }

    public function register(ContainerBuilder $container, \ReflectionClass $reflectionClass, AsTagged $attribute): void
    {
        if (!EnvironmentHelper::isPermittedEnvironment($container, $reflectionClass)) {
            return;
        }

        $container
            ->getDefinition(DefinitionHelper::getServiceId($container, $reflectionClass, $attribute->id))
            ->addTag($attribute->name, TagHelper::toTag($attribute))
        ;
    }
}
