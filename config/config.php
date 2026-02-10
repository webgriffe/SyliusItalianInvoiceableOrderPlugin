<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $containerConfigurator) {
    $parameters = $containerConfigurator->parameters();
    $parameters->set('sylius.form.type.address.validation_groups', [ 'Default' ]);
    $parameters->set('webgriffe_sylius_italian_invoiceable_order.taxation.eu_zone_code', 'EU');

    $containerConfigurator->import('packages/*.php');
};
