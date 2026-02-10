<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Context\Transform\AddressContext;

return static function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services();

    $services->set('webgriffe_sylius_italian_invoiceable_order.behat.context.transform.address', AddressContext::class)
        ->public()
        ->args([
            service('sylius.fixture.example_factory.address'),
            service('sylius.converter.country_name'),
        ])
    ;
};
