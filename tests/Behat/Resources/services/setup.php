<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Context\Setup\ChannelContext;
use Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Context\Setup\InvoiceableAddressContext;
use Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Context\Setup\ZoneContext;

return static function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services();

    $services->set('webgriffe_sylius_italian_invoiceable_order.behat.context.setup.channel', ChannelContext::class)
        ->args([
            service('sylius.behat.factory.default_channel'),
            service('webgriffe_sylius_italian_invoiceable_order.behat.factory.default_italy_channel'),
            service('sylius.behat.shared_storage'),
            service('sylius.factory.country'),
            service('sylius.repository.channel'),
            service('sylius.repository.country'),
        ])
        ->public()
    ;

    $services->set('webgriffe_sylius_italian_invoiceable_order.behat.context.setup.invoiceable_address', InvoiceableAddressContext::class)
        ->args([
            service('sylius.behat.shared_storage'),
            service('sylius.manager.customer'),
        ])
        ->public()
    ;

    $services->set('webgriffe_sylius_italian_invoiceable_order.behat.context.setup.zone', ZoneContext::class)
        ->args([
            service('sylius.factory.zone'),
            service('sylius.repository.zone'),
        ])
        ->public()
    ;
};
