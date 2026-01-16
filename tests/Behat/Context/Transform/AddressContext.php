<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Context\Transform;

use Behat\Behat\Context\Context;
use Faker\Factory;
use Faker\Generator;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Sylius\Component\Addressing\Converter\CountryNameConverterInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Webgriffe\SyliusItalianInvoiceableOrderPlugin\Model\ItalianInvoiceableAddressInterface;
use Webmozart\Assert\Assert;

final class AddressContext implements Context
{
    private Generator $fakerGenerator;

    public function __construct(
        private readonly ExampleFactoryInterface $exampleAddressFactory,
        private readonly CountryNameConverterInterface $countryNameConverter,
    ) {
        $this->fakerGenerator = Factory::create();
    }

    /**
     * @Transform /^address for the individual "([^"]+)" - "([^"]+)", "([^"]+)", "([^"]+)" - "([^"]+)"$/
     */
    public function createNewIndividualAddressWith(
        string $customerName,
        string $city,
        string $street,
        string $postcode,
        string $countryName,
    ): AddressInterface {
        [$firstName, $lastName] = explode(' ', $customerName);

        /** @var AddressInterface&ItalianInvoiceableAddressInterface $address */
        $address = $this->exampleAddressFactory->create([
            'country_code' => $this->countryNameConverter->convertToCode($countryName),
            'first_name' => $firstName,
            'last_name' => $lastName,
            'company' => null,
            'customer' => null,
            'phone_number' => null,
            'city' => $city,
            'street' => $street,
            'postcode' => $postcode,
        ]);

        $address->setBillingRecipientType(ItalianInvoiceableAddressInterface::BILLING_RECIPIENT_TYPE_INDIVIDUAL);

        return $address;
    }

    /**
     * @Transform /^address for the company "([^"]*)" - "([^"]*)" - "([^"]*)", "([^"]*)", "([^"]*)" - "([^"]*)"$/
     */
    public function createNewCompanyAddressWith(
        string $company,
        string $customerName,
        string $street,
        string $postcode,
        string $city,
        string $countryName,
    ): AddressInterface {
        [$firstName, $lastName] = explode(' ', $customerName);

        /** @var AddressInterface&ItalianInvoiceableAddressInterface $address */
        $address = $this->exampleAddressFactory->create([
            'country_code' => $this->countryNameConverter->convertToCode($countryName),
            'first_name' => $firstName,
            'last_name' => $lastName,
            'company' => $company,
            'customer' => null,
            'phone_number' => null,
            'city' => $city,
            'street' => $street,
            'postcode' => $postcode,
        ]);

        $address->setBillingRecipientType(ItalianInvoiceableAddressInterface::BILLING_RECIPIENT_TYPE_COMPANY);

        return $address;
    }

    /**
     * @Transform /^valid italian individual billing address$/
     *
     * @return AddressInterface&ItalianInvoiceableAddressInterface
     */
    public function createNewValidItalianIndividualBillingAddress()
    {
        /** @var AddressInterface|(AddressInterface&ItalianInvoiceableAddressInterface) $address */
        $address = $this->exampleAddressFactory->create(['country_code' => 'IT', 'customer' => null]);
        Assert::isInstanceOf($address, ItalianInvoiceableAddressInterface::class);
        $address->setBillingRecipientType(ItalianInvoiceableAddressInterface::BILLING_RECIPIENT_TYPE_INDIVIDUAL);
        // Taken random from: https://www.ilcodicefiscale.online/genera/
        $address->setTaxCode('MSCLBR02H18A662F');

        return $address;
    }

    /**
     * @Transform /^valid italian company billing address$/
     *
     * @return AddressInterface&ItalianInvoiceableAddressInterface
     */
    public function createNewValidItalianCompanyBillingAddress()
    {
        /** @var AddressInterface|(AddressInterface&ItalianInvoiceableAddressInterface) $address */
        $address = $this->exampleAddressFactory->create(['country_code' => 'IT', 'customer' => null]);
        Assert::isInstanceOf($address, ItalianInvoiceableAddressInterface::class);
        $address->setBillingRecipientType(ItalianInvoiceableAddressInterface::BILLING_RECIPIENT_TYPE_COMPANY);
        $address->setCompany($this->fakerGenerator->company);
        // Randomly generated but valid VAT number with http://www.sottomentitespoglie.it/GeneratoreAziende.aspx
        $vatNumberAndTaxCode = '02664480353';
        // most companies in Italy have the same VAT number and tax code
        $address->setVatNumber($vatNumberAndTaxCode);
        $address->setTaxCode($vatNumberAndTaxCode);
        $address->setSdiCode(strtoupper($this->fakerGenerator->bothify('*******')));

        return $address;
    }

    /**
     * @Transform /^valid german individual billing address$/
     *
     * @return AddressInterface&ItalianInvoiceableAddressInterface
     */
    public function createNewValidGermanIndividualBillingAddress()
    {
        /** @var AddressInterface|(AddressInterface&ItalianInvoiceableAddressInterface) $address */
        $address = $this->exampleAddressFactory->create(['country_code' => 'DE', 'customer' => null]);
        Assert::isInstanceOf($address, ItalianInvoiceableAddressInterface::class);
        $address->setBillingRecipientType(ItalianInvoiceableAddressInterface::BILLING_RECIPIENT_TYPE_INDIVIDUAL);

        return $address;
    }

    /**
     * @Transform /^valid german company billing address$/
     *
     * @return AddressInterface&ItalianInvoiceableAddressInterface
     */
    public function createNewValidGermanCompanyBillingAddress()
    {
        /** @var AddressInterface|(AddressInterface&ItalianInvoiceableAddressInterface) $address */
        $address = $this->exampleAddressFactory->create(['country_code' => 'DE', 'customer' => null]);
        Assert::isInstanceOf($address, ItalianInvoiceableAddressInterface::class);
        $address->setBillingRecipientType(ItalianInvoiceableAddressInterface::BILLING_RECIPIENT_TYPE_COMPANY);
        $address->setCompany($this->fakerGenerator->company);
        // This is the real german company Hetzner (https://www.hetzner.com/) VAT number
        $address->setVatNumber('DE812871812');

        return $address;
    }

    /**
     * @Transform /^valid US individual billing address$/
     *
     * @return AddressInterface&ItalianInvoiceableAddressInterface
     */
    public function createNewValidUSIndividualBillingAddress()
    {
        /** @var AddressInterface|(AddressInterface&ItalianInvoiceableAddressInterface) $address */
        $address = $this->exampleAddressFactory->create(['country_code' => 'US', 'customer' => null]);
        Assert::isInstanceOf($address, ItalianInvoiceableAddressInterface::class);
        $address->setBillingRecipientType(ItalianInvoiceableAddressInterface::BILLING_RECIPIENT_TYPE_INDIVIDUAL);

        return $address;
    }

    /**
     * @Transform /^valid US company billing address$/
     *
     * @return AddressInterface&ItalianInvoiceableAddressInterface
     */
    public function createNewValidUSCompanyBillingAddress()
    {
        /** @var AddressInterface|(AddressInterface&ItalianInvoiceableAddressInterface) $address */
        $address = $this->exampleAddressFactory->create(['country_code' => 'US', 'customer' => null]);
        Assert::isInstanceOf($address, ItalianInvoiceableAddressInterface::class);
        $address->setBillingRecipientType(ItalianInvoiceableAddressInterface::BILLING_RECIPIENT_TYPE_COMPANY);
        $address->setCompany($this->fakerGenerator->company);

        return $address;
    }
}
