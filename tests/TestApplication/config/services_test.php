<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $container) {
    if (str_starts_with($container->env(), 'test')) {
//parameters:
//    app.taxation.eu_zone_code: 'EU'
        $container->import('../../../vendor/sylius/sylius/src/Sylius/Behat/Resources/config/services.xml');
        $container->import('@WebgriffeSyliusItalianInvoiceableOrderPlugin/tests/Behat/Resources/services.xml');
    }
};
