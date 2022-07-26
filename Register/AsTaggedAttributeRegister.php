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

use FRZB\Component\DependencyInjection\Attribute\AsTagged;
use FRZB\Component\DependencyInjection\Helper\TagHelper;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Register #[AsService] attribute on definition that is autoconfigured.
 *
 * @author Mykhailo Shtanko <fractalzombie@gmail.com>
 */
class AsTaggedAttributeRegister extends AbstractAttributeRegister
{
    public function register(ContainerBuilder $container, \ReflectionClass $rClass, \ReflectionAttribute $rAttribute): void
    {
        $environment = $container->getParameter('kernel.environment');
        $attribute = self::getAttribute(AsTagged::class, $rAttribute);
        $definition = $container->getDefinition($rClass->getName());

        if ($attribute && !self::isPermittedEnvironmentOrEnvironmentIsNotDefined($environment, $rClass->getName())) {
            return;
        }

        $container->setDefinition(
            $definition->getClass(),
            $definition->addTag($attribute->name, TagHelper::toTag($attribute)),
        );
    }
}
