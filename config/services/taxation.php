<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Webgriffe\SyliusItalianInvoiceableOrderPlugin\Taxation\ItalianTaxCalculationStrategy;

return static function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services();

    $services->set('webgriffe_sylius_italian_invoiceable_order.strategy.taxation.tax_calculation.italian_tax_calculation_strategy', ItalianTaxCalculationStrategy::class)
        ->args([
            'italian_tax_calculation_strategy',
            tagged_iterator('sylius.taxation.item_units.applicator'),
            param('app.taxation.eu_zone_code'),
        ])
        ->tag('sylius.taxation.calculation_strategy', [
            'type' => 'italian_tax_calculation_strategy',
            'label' => 'Italian tax calculation',
        ])
    ;
};
