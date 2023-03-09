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

use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\Service;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\ServiceInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $configurator->services()
        ->load('FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\\', '../Fixtures/*')
        ->exclude('../Fixtures/{Attribute,Enum,Util,Request,Response,ValueObject,DTO,Data,Exception,UseCase,Tests}/*')
        ->autoconfigure()
        ->autowire()
        ->alias(ServiceInterface::class, Service::class)
    ;
};
