<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Webgriffe\SyliusItalianInvoiceableOrderPlugin\Model\ItalianInvoiceableAddressInterface;
use Webmozart\Assert\Assert;

final class InvoiceableAddressContext implements Context
{
    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;
    /**
     * @var ObjectManager
     */
    private $customerManager;
    /**
     * @var Generator
     */
    private $fakerGenerator;

    public function __construct(SharedStorageInterface $sharedStorage, ObjectManager $customerManager)
    {
        $this->sharedStorage = $sharedStorage;
        $this->customerManager = $customerManager;
        $this->fakerGenerator = Factory::create();
    }

    /**
     * @Given /^(I) have an (address for the company "([^"]*)" - "([^"]*)" - "([^"]*)", "([^"]*)", "([^"]*)" - "([^"]*)")$/
     */
    public function iHaveAnAddressForTheCompany(ShopUserInterface $user, AddressInterface $address): void
    {
        $this->sharedStorage->set('address', $address);
        /** @var CustomerInterface $customer */
        $customer = $user->getCustomer();

        $customer->addAddress($address);

        $this->customerManager->flush();
    }

    /**
     * @Given /^(this address) has also all the invoiceable information valid for an italian company$/
     */
    public function thisAddressHasAlsoTheFollowingInvoiceableInformation(AddressInterface $address)
    {
        // Randomly generated but valid VAT number with http://www.sottomentitespoglie.it/GeneratoreAziende.aspx
        $vatNumberAndTaxCode = '02664480353';

        /** @var ItalianInvoiceableAddressInterface $address */
        $address->setTaxCode($vatNumberAndTaxCode);
        $address->setVatNumber($vatNumberAndTaxCode);
        $address->setSdiCode(strtoupper($this->fakerGenerator->bothify('*******')));
        $address->setPecAddress($this->fakerGenerator->email);

        $this->customerManager->flush();
    }
}
