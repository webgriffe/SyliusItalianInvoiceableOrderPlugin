<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Webgriffe\SyliusItalianInvoiceableOrderPlugin\Validator\Constraints\EuropeanVatNumberValidator;

return static function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services();

    $services->set('webgriffe_sylius_italian_invoiceable_order.validator.european_vat_number', EuropeanVatNumberValidator::class)
        ->args([
            service('webgriffe_sylius_italian_invoiceable_order.vies'),
            service('logger'),
        ])
        ->tag('validator.constraint_validator', [
            'alias' => 'webgriffe_sylius_italian_invoiceable_order.european_vat_number_validator',
        ])
    ;
};
