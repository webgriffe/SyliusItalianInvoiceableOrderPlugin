<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Context\Ui\Shop\Checkout;

use Faker\Factory;
use Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Page\Shop\Checkout\AddressPageInterface;
use Behat\Behat\Context\Context;
use Faker\Generator;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Webmozart\Assert\Assert;

final class CheckoutAddressingContext implements Context
{
    /**
     * @var AddressPageInterface
     */
    private $addressPage;
    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;
    /**
     * @var Generator
     */
    private $fakerGenerator;

    public function __construct(AddressPageInterface $addressPage, SharedStorageInterface $sharedStorage)
    {
        $this->addressPage = $addressPage;
        $this->sharedStorage = $sharedStorage;
        $this->fakerGenerator = Factory::create();
    }

    /**
     * @When /^I specify the billing (address for the individual "([^"]*)" - "([^"]*)", "([^"]*)", "([^"]*)" - "([^"]*)")$/
     * @When /^I specify the billing (address for the company "([^"]*)" - "([^"]*)" - "([^"]*)", "([^"]*)", "([^"]*)" - "([^"]*)")$/
     */
    public function iSpecifyTheBillingAddressForTheIndividual(AddressInterface $address): void
    {
        $key = sprintf(
            'billing_address_%s_%s',
            strtolower((string) $address->getFirstName()),
            strtolower((string) $address->getLastName())
        );
        $this->sharedStorage->set($key, $address);

        $this->addressPage->specifyBillingAddress($address);
    }

    /**
     * @When /^I specify a valid billing individual tax code$/
     */
    public function iSpecifyAValidIndividualTaxCode(): void
    {
        $this->addressPage->specifyBillingTaxCode($this->fakerGenerator->ssn);
    }

    /**
     * @When /^I specify a valid billing VAT number$/
     */
    public function iSpecifyAValidBillingVatNumber(): void
    {
        $this->addressPage->specifyBillingVatNumber($this->fakerGenerator->bothify('######'));
    }

    /**
     * @When /^I specify a valid billing tax code and VAT number for an italian company$/
     */
    public function iSpecifyAValidBillingVatNumberForAnItalianCompany(): void
    {
        // Randomly generated but valid VAT number with http://www.sottomentitespoglie.it/GeneratoreAziende.aspx
        $vatNumberAndTaxCode = '02664480353';
        // most companies in Italy have the same VAT number and tax code
        $this->addressPage->specifyBillingTaxCode($vatNumberAndTaxCode);
        $this->addressPage->specifyBillingVatNumber($vatNumberAndTaxCode);
    }

    /**
     * @When /^I specify a valid billing SDI code$/
     */
    public function iSpecifyAValidBillingSdiCode()
    {
        $this->addressPage->specifyBillingSdiCode($this->fakerGenerator->numerify('#######'));
    }

    /**
     * @When /^I specify a valid billing PEC address$/
     */
    public function iSpecifyAValidBillingPecAddress()
    {
        $this->addressPage->specifyBillingPecAddress($this->fakerGenerator->email);
    }

    /**
     * @When /^I want to ship to a different shipping address$/
     */
    public function iWantToShipToADifferentShippingAddress(): void
    {
        $this->addressPage->chooseDifferentShippingAddress();
    }

    /**
     * @Then /^I should not see any invoicing related field in the shipping address form$/
     */
    public function iShouldNotSeeAnyInvoicingRelatedFieldInTheShippingAddressForm()
    {
        Assert::true($this->addressPage->isWithoutAnyInvoicingRelatedFieldInShippingAddress());
    }

    /**
     * @Given /^I specify a valid billing tax code for an italian individual$/
     */
    public function iSpecifyAValidBillingTaxCodeForAnItalianIndividual()
    {
        // Taken random from: https://www.ilcodicefiscale.online/genera/
        $this->addressPage->specifyBillingTaxCode('MCLMBL06S44F839X');
    }

    /**
     * @When /^I specify an invalid tax code for an italian individual$/
     */
    public function iSpecifyAnInvalidTaxCodeForAnItalianIndividual(): void
    {
        $this->addressPage->specifyBillingTaxCode($this->fakerGenerator->bothify('??????##?##?###?'));
    }

    /**
     * @Then /^I should be notified that the billing tax code is not valid$/
     */
    public function iShouldBeNotifiedThatTheTaxCodeIsNotValid(): void
    {
        Assert::true($this->addressPage->checkValidationMessageFor('billing_tax_code', 'Please enter a valid italian tax code.'));
    }

    /**
     * @Given /^I specify an invalid tax code for an italian company$/
     */
    public function iSpecifyAnInvalidTaxCodeForAnItalianCompany(): void
    {
        $this->addressPage->specifyBillingTaxCode($this->fakerGenerator->numerify('###########'));
    }

    /**
     * @Given /^I specify an invalid VAT number for an italian company$/
     */
    public function iSpecifyAnInvalidVATNumberForAnItalianCompany(): void
    {
        $this->addressPage->specifyBillingVatNumber($this->fakerGenerator->numerify('###########'));
    }

    /**
     * @Then /^I should be notified that the billing VAT number is not valid$/
     */
    public function iShouldBeNotifiedThatTheBillingVATNumberIsNotValid(): void
    {
        Assert::true(
            $this->addressPage->checkValidationMessageFor('billing_vat_number', 'Please enter a valid italian VAT number.') ||
            $this->addressPage->checkValidationMessageFor('billing_vat_number', 'Please enter a valid european VAT number.')
        );
    }

    /**
     * @Given /^I specify an invalid SDI code$/
     */
    public function iSpecifyAnInvalidSDICode(): void
    {
        $this->addressPage->specifyBillingSdiCode($this->fakerGenerator->bothify('??##'));
    }

    /**
     * @Then /^I should be notified that the billing SDI code is not valid$/
     */
    public function iShouldBeNotifiedThatTheBillingSDICodeIsNotValid(): void
    {
        Assert::true(
            $this->addressPage->checkValidationMessageFor(
                'billing_sdi_code',
                'Please enter a valid SDI code.'
            )
        );
    }

    /**
     * @Given /^I specify an invalid PEC address$/
     */
    public function iSpecifyAnInvalidPECAddress(): void
    {
        $this->addressPage->specifyBillingPecAddress($this->fakerGenerator->bothify('?????##@not-valid'));
    }

    /**
     * @Then /^I should be notified that the billing PEC address is not valid$/
     */
    public function iShouldBeNotifiedThatTheBillingPECAddressIsNotValid(): void
    {
        Assert::true(
            $this->addressPage->checkValidationMessageFor('billing_pec_address', 'Please enter a valid PEC address.')
        );
    }

    /**
     * @Then /^I should be notified that the billing tax code is required$/
     */
    public function iShouldBeNotifiedThatTheBillingTaxCodeIsRequired(): void
    {
        Assert::true(
            $this->addressPage->checkValidationMessageFor('billing_tax_code', 'Tax code should not be blank.')
        );
    }

    /**
     * @Then /^I should be notified that the billing VAT number is required$/
     */
    public function iShouldBeNotifiedThatTheBillingVatNumberIsRequired()
    {
        Assert::true(
            $this->addressPage->checkValidationMessageFor('billing_vat_number', 'VAT number should not be blank.')
        );
    }

    /**
     * @Then /^I should be notified that the billing SDI code is required$/
     */
    public function iShouldBeNotifiedThatTheBillingSdiCodeIsRequired()
    {
        Assert::true(
            $this->addressPage->checkValidationMessageFor('billing_sdi_code', 'SDI code should not be blank.')
        );
    }

    /**
     * @Then /^I should be notified that the billing company name is required$/
     */
    public function iShouldBeNotifiedThatTheBillingCompanyNameIsRequired()
    {
        Assert::true(
            $this->addressPage->checkValidationMessageFor('billing_company', 'Company name should not be blank.')
        );
    }

    /**
     * @When /^I specify an invalid VAT number for a german company$/
     */
    public function iSpecifyAnInvalidVatNumberForAGermanCompany(): void
    {
        $this->addressPage->specifyBillingVatNumber($this->fakerGenerator->numerify('DE#########'));
    }

    /**
     * @When /^I specify a valid billing VAT number for a german company$/
     */
    public function iSpecifyAValidBillingVatNumberForAGermanCompany(): void
    {
        // This is the real german company Hetzner (https://www.hetzner.com/) VAT number
        $this->addressPage->specifyBillingVatNumber('DE812871812');
    }

    /**
     * @Given /^I do not specify the billing recipient type in the billing address$/
     */
    public function iDoNotSpecifyTheBillingRecipientTypeInTheBillingAddress(): void
    {
        // Nothing to do to not specify the billing recipient type
    }
}
