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

namespace FRZB\Component\DependencyInjection;

use FRZB\Component\DependencyInjection\Attribute\AsDecorated;
use FRZB\Component\DependencyInjection\Attribute\AsDeprecated;
use FRZB\Component\DependencyInjection\Attribute\AsService;
use FRZB\Component\DependencyInjection\Compiler\RegisterAsAliasAttributesPass;
use FRZB\Component\DependencyInjection\Compiler\RegisterAttributePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DependencyInjectionBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container
            ->addCompilerPass(new RegisterAttributePass(AsService::class))
            ->addCompilerPass(new RegisterAttributePass(AsDecorated::class))
            ->addCompilerPass(new RegisterAttributePass(AsDeprecated::class))
            ->addCompilerPass(new RegisterAsAliasAttributesPass())
        ;
    }
}
