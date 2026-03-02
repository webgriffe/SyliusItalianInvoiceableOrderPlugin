# Upgrade plugin guide

## Upgrade from version v0.x to v1.x

The v2 is now compatible with Sylius 2.x, so you need to update your Sylius version to 2.x before upgrading the plugin. Some changes not listed here may be required, so please refer to the Sylius 2.x upgrade guide for more details.

- The route `@WebgriffeSyliusTableRateShippingPlugin/Resources/config/config.yml` has been renamed to `@WebgriffeSyliusTableRateShippingPlugin/config/config.yaml`.
- The route `@WebgriffeSyliusTableRateShippingPlugin/Resources/config/admin_routing.yml` has been renamed to `@WebgriffeSyliusTableRateShippingPlugin/config/routes/admin.yaml`.
- The route `webgriffe_sylius_table_rate_shipping_plugin_shop` has been removed as it was unnecessary
- The migrations are now stored inside the plugin in `src/Migrations`. These should be idempotent, so if the changes made by these migrations are already present, they will do nothing.
- Important template changes:
  - Template `@WebgriffeSyliusItalianInvoiceableOrderPlugin/Common/_invoiceableAddressInfo.html.twig` has been renamed to `@WebgriffeSyliusItalianInvoiceableOrderPlugin/shared/address/billingAddressInfo.html.twig`
  - Template `@WebgriffeSyliusItalianInvoiceableOrderPlugin/Checkout/Address/_addressBookSelectInvoiceableDataAttributes.html.twig` has been removed as Sylius 2 uses UI component for this, so you can remove this template as it is no longer needed. If you have customized this template, you can move the customizations to the new UI component. If you are using the [Sylius Legacy Bundle](https://github.com/Sylius/LegacyShopBridgePlugin) you can still use this template, here is the latest implemenation:
    ```twig
    data-billing-recipient-type="{{ address.billingRecipientType }}"
    data-tax-code="{{ address.taxCode }}"
    data-vat-number="{{ address.vatNumber }}"
    data-sdi-code="{{ address.sdiCode }}"
    data-pec-address="{{ address.pecAddress }}"
    ```

## Upgrade from version v1.x to v2.x

- The service `app.taxation.italian_tax_calculation_strategy` has been renamed to `webgriffe_sylius_italian_invoiceable_order.strategy.taxation.tax_calculation.italian_tax_calculation_strategy`.
- The class `Webgriffe\SyliusItalianInvoiceableOrderPlugin\Model\ItalianTaxCalculationStrategy` has been moved to `Webgriffe\SyliusItalianInvoiceableOrderPlugin\Taxation\ItalianTaxCalculationStrategy`.
- The package `sandwich/vies-bundle` has been removed.
- The constraint `Symfony\Component\Validator\Constraints\Sandwich\ViesBundle\Validator\Constraint\VatNumber` has been replaced with `Webgriffe\SyliusItalianInvoiceableOrderPlugin\Validator\Constraints\EuropeanVatNumber`. Please update your validation rules accordingly with search and replace.
  Please, note also that now is available a new strict option that allows you to block the checkout step if the VIES service is not available. You can enable it by setting the `strict` option to `true` in your validation rules.
- The file `@WebgriffeSyliusItalianInvoiceableOrderPlugin/config/config.yaml` has been renamed to `@WebgriffeSyliusItalianInvoiceableOrderPlugin/config/config.php`.
- The parameter `app.taxation.eu_zone_code` has been renamed to `webgriffe_sylius_italian_invoiceable_order.taxation.eu_zone_code`.
