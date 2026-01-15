<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Page\Shop\Checkout;

use Sylius\Behat\Page\Shop\Checkout\AddressPage as BaseAddressPage;
use Sylius\Component\Core\Model\AddressInterface;
use Webgriffe\SyliusItalianInvoiceableOrderPlugin\Model\ItalianInvoiceableAddressInterface;
use Webmozart\Assert\Assert;

class AddressPage extends BaseAddressPage implements AddressPageInterface
{
    /**
     * @param AddressInterface|(AddressInterface&ItalianInvoiceableAddressInterface) $billingAddress
     */
    public function specifyBillingAddress(AddressInterface $billingAddress): void
    {
        Assert::isInstanceOf($billingAddress, ItalianInvoiceableAddressInterface::class);
        if (null !== $billingAddress->getBillingRecipientType()) {
            $this->getElement(sprintf('%s_billing_recipient_type', BaseAddressPage::TYPE_BILLING))->selectOption($billingAddress->getBillingRecipientType());
        }
        parent::specifyBillingAddress($billingAddress);
        if (null !== $billingAddress->getCompany()) {
            $this->waitForElement(5, sprintf('%s_company', BaseAddressPage::TYPE_BILLING));
            $this->getElement(sprintf('%s_company', BaseAddressPage::TYPE_BILLING))->setValue($billingAddress->getCompany());
        }
    }

    public function specifyBillingTaxCode(string $ssn): void
    {
        $this->getElement('billing_tax_code')->setValue($ssn);
    }

    public function specifyBillingVatNumber(string $vatNumber): void
    {
        $this->getElement('billing_vat_number')->setValue($vatNumber);
    }

    public function specifyBillingSdiCode(string $sdiCode): void
    {
        $this->getElement('billing_sdi_code')->setValue($sdiCode);
    }

    public function specifyBillingPecAddress(string $pecAddress): void
    {
        $this->getElement('billing_pec_address')->setValue($pecAddress);
    }

    protected function getDefinedElements(): array
    {
        return array_merge(
            [
                'billing_billing_recipient_type' => '[data-test-billing-address] [data-test-billing-recipient-type] input',
                'billing_tax_code' => '[data-test-billing-address] [data-test-tax-code]',
                'billing_company' => '[data-test-billing-address] [data-test-company]',
                'billing_vat_number' => '[data-test-billing-address] [data-test-vat-number]',
                'billing_sdi_code' => '[data-test-billing-address] [data-test-sdi-code]',
                'billing_pec_address' => '[data-test-billing-address] [data-test-pec-address]',
                'shipping_billing_recipient_type' => '[data-test-shipping-address] [data-test-billing-recipient-type] input',
                'shipping_tax_code' => '[data-test-shipping-address] [data-test-tax-code]',
                'shipping_vat_number' => '[data-test-shipping-address] [data-test-vat-number]',
                'shipping_sdi_code' => '[data-test-shipping-address] [data-test-sdi-code]',
                'shipping_pec_address' => '[data-test-shipping-address] [data-test-pec-address]',
            ],
            parent::getDefinedElements(),
        );
    }

    private function waitForElement(int $timeout, string $elementName): void
    {
        $this->getDocument()->waitFor($timeout, function () use ($elementName) {
            return $this->hasElement($elementName);
        });
    }

    public function isWithoutAnyInvoicingRelatedFieldInShippingAddress(): bool
    {
        return !$this->hasElement('shipping_billing_recipient_type') &&
            !$this->hasElement('shipping_tax_code') &&
            !$this->hasElement('shipping_vat_number') &&
            !$this->hasElement('shipping_sdi_code') &&
            !$this->hasElement('shipping_pec_address');
    }

    public function getPreFilledBillingRecipientType(): string
    {
        return $this->getStringValue('billing_billing_recipient_type');
    }

    public function getPreFilledBillingTaxCode(): string
    {
        return $this->getStringValue('billing_tax_code');
    }

    public function getPreFilledBillingVatNumber(): string
    {
        return $this->getStringValue('billing_vat_number');
    }

    public function getPreFilledBillingSdiCode(): string
    {
        return $this->getStringValue('billing_sdi_code');
    }

    public function getPreFilledBillingPecAddress(): string
    {
        return $this->getStringValue('billing_pec_address');
    }

    private function getStringValue(string $element): string
    {
        $value = $this->getElement($element)->getValue();
        Assert::string($value);

        return $value;
    }
}
