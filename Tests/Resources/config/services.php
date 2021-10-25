<?php

declare(strict_types=1);

use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\Service;
use FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\Service\ServiceInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $configurator->services()
        ->load('FRZB\Component\DependencyInjection\Tests\Resources\Fixtures\\', '../Fixtures/*')
        ->exclude('../Fixtures/{Attribute,Util,Request,Response,ValueObject,DTO,Data,Exception,UseCase,Tests}/*')
        ->autoconfigure()
        ->autowire()
        ->alias(ServiceInterface::class, Service::class)
    ;
};
