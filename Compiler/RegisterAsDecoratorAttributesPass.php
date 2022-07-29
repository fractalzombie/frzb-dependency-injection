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

use FRZB\Component\DependencyInjection\Attribute\AsDecorator;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @internal
 *
 * Register #[AsDecorator] attribute on definition that is autowired.
 *
 * @author Mykhailo Shtanko <fractalzombie@gmail.com>
 */
final class RegisterAsDecoratorAttributesPass extends AbstractRegisterAttributePass
{
    #[Pure]
    public function __construct()
    {
        parent::__construct(AsDecorator::class);
    }

    public function register(ContainerBuilder $container, \ReflectionClass $rClass, AsDecorator $attribute): void
    {
        $container->getDefinition($rClass->getName())
            ->setDecoratedService($attribute->decorates, $attribute->innerName, $attribute->priority, $attribute->onInvalid->value)
        ;
    }

    protected function accept(Definition $definition): bool
    {
        return $definition->isAutowired() && $this->isAttributesIgnored($definition);
    }
}
