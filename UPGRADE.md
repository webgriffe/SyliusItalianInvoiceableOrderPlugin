# Upgrade plugin guide

## Upgrade from version 1.x to 2.x

- The service `app.taxation.italian_tax_calculation_strategy` has been renamed to `webgriffe_sylius_italian_invoiceable_order.strategy.taxation.tax_calculation.italian_tax_calculation_strategy`.
- The class `Webgriffe\SyliusItalianInvoiceableOrderPlugin\Model\ItalianTaxCalculationStrategy` has been moved to `Webgriffe\SyliusItalianInvoiceableOrderPlugin\Taxation\ItalianTaxCalculationStrategy`.
- 
