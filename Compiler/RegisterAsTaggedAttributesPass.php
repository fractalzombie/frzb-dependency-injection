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

use FRZB\Component\DependencyInjection\Attribute\AsTagged;
use FRZB\Component\DependencyInjection\Helper\EnvironmentHelper;
use FRZB\Component\DependencyInjection\Helper\TagHelper;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Register #[AsTagged] attribute on definition that is autoconfigured.
 *
 * @author Mykhailo Shtanko <fractalzombie@gmail.com>
 */
final class RegisterAsTaggedAttributesPass extends AbstractRegisterAttributePass
{
    #[Pure]
    public function __construct()
    {
        parent::__construct(AsTagged::class);
    }

    public function register(ContainerBuilder $container, \ReflectionClass $rClass, AsTagged $attribute): void
    {
        if (!EnvironmentHelper::isPermittedEnvironment($container, $rClass->getName())) {
            return;
        }

        $container->getDefinition($rClass->getName())
            ->addTag($attribute->name, TagHelper::toTag($attribute))
        ;
    }

    protected function accept(Definition $definition): bool
    {
        return $definition->isAutoconfigured() && $this->isAttributesIgnored($definition);
    }
}
