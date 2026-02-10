<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container): void {
    $container->extension('sylius_twig_hooks', [
        'hooks' => [
            'sylius_shop.shared.address' => [
                'company' => [
                    'enabled' => false,
                ],
                'name' => [
                    'enabled' => false,
                ],
                'billingAddressInfo' => [
                    'template' => '@WebgriffeSyliusItalianInvoiceableOrderPlugin/shop/shared/address/billingAddressInfo.html.twig',
                    'priority' => 1000,
                ],
            ],
            'sylius_shop.shared.form.address' => [
                'billingRecipientType' => [
                    'template' => '@WebgriffeSyliusItalianInvoiceableOrderPlugin/shop/shared/form/address/billingRecipientType.html.twig',
                    'priority' => 1000,
                ],
                'invoiceableBillingData' => [
                    'template' => '@WebgriffeSyliusItalianInvoiceableOrderPlugin/shop/shared/form/address/invoiceableBillingData.html.twig',
                    'priority' => 650,
                ],
            ],
        ],
    ]);
};
