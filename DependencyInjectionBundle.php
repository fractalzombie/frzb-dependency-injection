<?php

declare(strict_types=1);

/**
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 *
 * Copyright (c) 2024 Mykhailo Shtanko fractalzombie@gmail.com
 *
 * For the full copyright and license information, please view the LICENSE.MD
 * file that was distributed with this source code.
 */

namespace FRZB\Component\DependencyInjection;

use FRZB\Component\DependencyInjection\Compiler\RegisterAsAliasAttributesPass;
use FRZB\Component\DependencyInjection\Compiler\RegisterAsDecoratorAttributesPass;
use FRZB\Component\DependencyInjection\Compiler\RegisterAsDeprecatedAttributesPass;
use FRZB\Component\DependencyInjection\Compiler\RegisterAsIgnoredAttributesPass;
use FRZB\Component\DependencyInjection\Compiler\RegisterAsServiceAttributePass;
use FRZB\Component\DependencyInjection\Compiler\RegisterAsTaggedAttributesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class DependencyInjectionBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container
            ->addCompilerPass(new RegisterAsAliasAttributesPass())
            ->addCompilerPass(new RegisterAsServiceAttributePass())
            ->addCompilerPass(new RegisterAsTaggedAttributesPass())
            ->addCompilerPass(new RegisterAsIgnoredAttributesPass())
            ->addCompilerPass(new RegisterAsDecoratorAttributesPass())
            ->addCompilerPass(new RegisterAsDeprecatedAttributesPass())
        ;
    }
}
