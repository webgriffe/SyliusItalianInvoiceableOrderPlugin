<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

return static function (ContainerConfigurator $container): void {
    $container->extension('sylius_twig_hooks', [
        'hooks' => [
            'sylius_admin.order.update.content.form.billing_address' => [
                'billingRecipientType' => [
                    'template' => '@WebgriffeSyliusItalianInvoiceableOrderPlugin/admin/shared/form/address/billingRecipientType.html.twig',
                    'priority' => 1000,
                ],
                'invoiceableBillingData' => [
                    'template' => '@WebgriffeSyliusItalianInvoiceableOrderPlugin/admin/shared/form/address/invoiceableBillingData.html.twig',
                    'priority' => 650,
                ],
            ],
            'sylius_admin.order.update.content.form.shipping_address' => [
                'billingRecipientType' => [
                    'template' => '@WebgriffeSyliusItalianInvoiceableOrderPlugin/admin/shared/form/address/billingRecipientType.html.twig',
                    'priority' => 1000,
                ],
                'invoiceableBillingData' => [
                    'template' => '@WebgriffeSyliusItalianInvoiceableOrderPlugin/admin/shared/form/address/invoiceableBillingData.html.twig',
                    'priority' => 650,
                ],
            ],
        ],
    ]);
};
