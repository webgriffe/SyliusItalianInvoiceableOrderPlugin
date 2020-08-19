<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Page\Shop\Checkout;

use Sylius\Behat\Page\Shop\Checkout\AddressPageInterface as BaseAddressPageInterface;

interface AddressPageInterface extends BaseAddressPageInterface
{
    public function specifyBillingTaxCode(string $ssn): void;

    public function specifyBillingVatNumber(string $vatNumber): void;

    public function specifyBillingSdiCode(string $sdiCode): void ;

    public function specifyBillingPecAddress(string $pecAddress): void;

    public function isWithoutAnyInvoicingRelatedFieldInShippingAddress(): bool;
}
