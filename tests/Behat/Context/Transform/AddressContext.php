<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Context\Transform;

use Behat\Behat\Context\Context;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Sylius\Component\Addressing\Converter\CountryNameConverterInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Webgriffe\SyliusItalianInvoiceableOrderPlugin\Model\ItalianInvoiceableAddressInterface;

final class AddressContext implements Context
{
    /**
     * @var ExampleFactoryInterface
     */
    private $exampleAddressFactory;
    /**
     * @var CountryNameConverterInterface
     */
    private $countryNameConverter;

    public function __construct(
        ExampleFactoryInterface $exampleAddressFactory,
        CountryNameConverterInterface $countryNameConverter
    ) {
        $this->exampleAddressFactory = $exampleAddressFactory;
        $this->countryNameConverter = $countryNameConverter;
    }

    /**
     * @Transform /^address for the individual "([^"]+)" - "([^"]+)", "([^"]+)", "([^"]+)" - "([^"]+)"$/
     */
    public function createNewIndividualAddressWith(
        $customerName,
        $city,
        $street,
        $postcode,
        $countryName
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
        $company,
        $customerName,
        $city,
        $street,
        $postcode,
        $countryName
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
}
