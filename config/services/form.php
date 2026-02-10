<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Sylius\Bundle\AddressingBundle\Form\Type\AddressType;
use Webgriffe\SyliusItalianInvoiceableOrderPlugin\Form\Extension\ItalianInvoiceableAddressTypeExtension;

return static function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services();

    $services->set('webgriffe_sylius_italian_invoiceable_order.form.extension.italian_invoiceable_address', ItalianInvoiceableAddressTypeExtension::class)
        ->tag('form.type_extension', [
            'extended_type' => AddressType::class,
        ])
    ;
};
