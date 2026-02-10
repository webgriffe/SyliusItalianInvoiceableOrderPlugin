<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Service\DefaultItalyChannelFactory;
use Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Service\InMemoryEuropeanVatNumberValidator;

return static function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services();

    $services->set('webgriffe_sylius_italian_invoiceable_order.behat.factory.default_italy_channel', DefaultItalyChannelFactory::class)
        ->args([
            service('sylius.repository.channel'),
            service('sylius.repository.country'),
            service('sylius.repository.currency'),
            service('sylius.repository.locale'),
            service('sylius.repository.zone'),
            service('sylius.factory.channel'),
            service('sylius.factory.country'),
            service('sylius.factory.currency'),
            service('sylius.factory.locale'),
            service('sylius.factory.zone'),
            param('locale'),
        ])
    ;

    $services->set('webgriffe_sylius_italian_invoiceable_order.validator.european_vat_number', InMemoryEuropeanVatNumberValidator::class)
        ->tag('validator.constraint_validator', [
            'alias' => 'webgriffe_sylius_italian_invoiceable_order.european_vat_number_validator',
        ])
    ;
};
