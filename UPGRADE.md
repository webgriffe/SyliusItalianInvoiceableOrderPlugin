# Upgrade plugin guide

## Upgrade from version 1.x to 2.x

- The service `app.taxation.italian_tax_calculation_strategy` has been renamed to `webgriffe_sylius_italian_invoiceable_order.strategy.taxation.tax_calculation.italian_tax_calculation_strategy`.
- The class `Webgriffe\SyliusItalianInvoiceableOrderPlugin\Model\ItalianTaxCalculationStrategy` has been moved to `Webgriffe\SyliusItalianInvoiceableOrderPlugin\Taxation\ItalianTaxCalculationStrategy`.
- The package `sandwich/vies-bundle` has been removed.
- The constraint `Symfony\Component\Validator\Constraints\Sandwich\ViesBundle\Validator\Constraint\VatNumber` has been replaced with `Webgriffe\SyliusItalianInvoiceableOrderPlugin\Validator\Constraints\EuropeanVatNumber`. Please update your validation rules accordingly with search and replace.
  Please, note also that now is available a new strict option that allows you to block the checkout step if the VIES service is not available. You can enable it by setting the `strict` option to `true` in your validation rules.
- The file `@WebgriffeSyliusItalianInvoiceableOrderPlugin/config/config.yaml` has been renamed to `@WebgriffeSyliusItalianInvoiceableOrderPlugin/config/config.php`.
- The parameter `app.taxation.eu_zone_code` has been renamed to `webgriffe_sylius_italian_invoiceable_order.taxation.eu_zone_code`.
