<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Webgriffe\SyliusItalianInvoiceableOrderPlugin\Model\ItalianTaxCalculationStrategy;

return static function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services();

    $services->set('app.taxation.italian_tax_calculation_strategy', ItalianTaxCalculationStrategy::class)
        ->args([
            'italian_tax_calculation_strategy',
            [
                service('sylius.applicator.taxation.order_item_units'),
                service('sylius.applicator.taxation.order_shipment'),
            ],
            param('app.taxation.eu_zone_code'),
        ])
        ->tag('sylius.taxation.calculation_strategy', [
            'type' => 'italian_tax_calculation_strategy',
            'label' => 'Italian tax calculation',
        ])
    ;
};
