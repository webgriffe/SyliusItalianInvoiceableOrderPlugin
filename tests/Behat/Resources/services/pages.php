<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Page\Shop\Checkout\AddressPage;
use Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Page\Shop\Checkout\CompletePage;

return static function (ContainerConfigurator $containerConfigurator) {
    $services = $containerConfigurator->services();

    $services->set('webgriffe_sylius_italian_invoiceable_order.behat.page.shop.checkout.address', AddressPage::class)
        ->parent('sylius.behat.page.shop.checkout.address')
        ->public()
    ;

    $services->set('webgriffe_sylius_italian_invoiceable_order.behat.page.shop.checkout.complete', CompletePage::class)
        ->parent('sylius.behat.page.shop.checkout.complete')
        ->public()
    ;
};
