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

use FRZB\Component\DependencyInjection\Attribute\AsAlias;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Reads #[AsAlias] attributes on aliases.
 *
 * @author Mykhailo Shtanko <fractalzombie@gmail.com>
 */
class RegisterAsAliasAttributesPass extends RegisterAttributePass
{
    #[Pure]
    public function __construct()
    {
        parent::__construct(AsAlias::class);
    }

    /** {@inheritdoc} */
    public function process(ContainerBuilder $container): void
    {
        foreach ($container->getAliases() as $id => $alias) {
            if ($class = $container->getReflectionClass($id, false)) {
                $this->processClass($container, $class);
            }
        }
    }
}
