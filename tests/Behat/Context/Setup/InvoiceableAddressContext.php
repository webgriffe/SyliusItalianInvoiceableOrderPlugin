<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
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
     * @var \Doctrine\Common\Persistence\ObjectManager|\Doctrine\Persistence\ObjectManager
     */
    private $customerManager;

    /**
     * @var Generator
     */
    private $fakerGenerator;

    public function __construct(SharedStorageInterface $sharedStorage, /*ObjectManager*/ $customerManager)
    {
        //With the change from Sylius 1.8 to 1.9 the ObjectManager changed place. In order to make this plugin
        //compatible with both versions, the type hint was removed from the arguments list and moved here as an
        //explicit check
        Assert::isInstanceOfAny(
            $customerManager,
            ['\Doctrine\Common\Persistence\ObjectManager', '\Doctrine\Persistence\ObjectManager']
        );
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
