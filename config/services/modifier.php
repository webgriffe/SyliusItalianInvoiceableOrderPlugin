<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Webgriffe\SyliusItalianInvoiceableOrderPlugin\Modifier\AddressFormValuesModifier;

return static function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services();

    $services->set('webgriffe_sylius_italian_invoiceable_order.modifier.address_form_values', AddressFormValuesModifier::class)
        ->tag('sylius_shop.modifier.address_form_values')
    ;
};
