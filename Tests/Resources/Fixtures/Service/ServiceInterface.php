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

namespace FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service;

use FRZB\Component\DependencyInjection\Attribute\AsAlias;

/** @internal */
#[AsAlias(Service::class)]
#[AsAlias(ServiceWithEnvParameter::class, aliasForArgument: '$serviceWithArgument')]
#[AsAlias(DeprecatedService::class, aliasForArgument: '$serviceDeprecated')]
#[AsAlias(ServiceWithWhenAttribute::class, aliasForArgument: '$serviceWithWhenAttribute')]
#[AsAlias(ServiceWithCorrectWhenAttribute::class, aliasForArgument: '$serviceWithCorrectWhenAttribute')]
interface ServiceInterface
{
}
