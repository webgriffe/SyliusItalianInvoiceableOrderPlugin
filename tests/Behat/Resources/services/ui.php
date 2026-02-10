<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Context\Ui\Shop\Checkout\CheckoutAddressingContext;
use Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Context\Ui\Shop\Checkout\CheckoutCompleteContext;

return static function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services();

    $services->set('webgriffe_sylius_italian_invoiceable_order.behat.context.ui.shop.checkout.addressing', CheckoutAddressingContext::class)
        ->args([
            service('webgriffe_sylius_italian_invoiceable_order.behat.page.shop.checkout.address'),
            service('sylius.behat.shared_storage'),
        ])
        ->public()
    ;

    $services->set('webgriffe_sylius_italian_invoiceable_order.behat.context.ui.shop.checkout.complete', CheckoutCompleteContext::class)
        ->args([
            service('webgriffe_sylius_italian_invoiceable_order.behat.page.shop.checkout.complete'),
        ])
        ->public()
    ;
};
