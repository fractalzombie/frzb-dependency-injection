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
use FRZB\Component\DependencyInjection\Helper\EnvironmentHelper;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @internal
 *
 * Register #[AsDecorator] attribute on definition that is autowired
 *
 * @author Mykhailo Shtanko <fractalzombie@gmail.com>
 */
final class RegisterAsDecoratorAttributesPass extends AbstractRegisterAttributePass
{
    public function __construct()
    {
        parent::__construct(AsDecorator::class);
    }

    public function register(ContainerBuilder $container, \ReflectionClass $reflectionClass, AsDecorator $attribute): void
    {
        if (!EnvironmentHelper::isPermittedEnvironment($container, $reflectionClass->getName())) {
            return;
        }
        
        $container->getDefinition($reflectionClass->getName())
            ->setDecoratedService($attribute->decorates, $attribute->innerName, $attribute->priority, $attribute->onInvalid->value)
        ;
    }

    protected function accept(Definition $definition): bool
    {
        return $definition->isAutowired() && $this->isAttributesIgnored($definition);
    }
}
