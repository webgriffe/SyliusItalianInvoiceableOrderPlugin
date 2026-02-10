<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Webgriffe\SyliusItalianInvoiceableOrderPlugin\Comparator\ItalianInvoiceableAddressComparatorDecorator;

return static function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services();

    $services->set('webgriffe_sylius_italian_invoiceable_order.address_comparator', ItalianInvoiceableAddressComparatorDecorator::class)
        ->decorate('sylius.comparator.address')
        ->args([
            service('.inner'),
        ])
    ;
};
