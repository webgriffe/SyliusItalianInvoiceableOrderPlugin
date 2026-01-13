<?php

declare(strict_types=1);

namespace Webgriffe\SyliusItalianInvoiceableOrderPlugin\Modifier;

use Sylius\Bundle\ShopBundle\Modifier\AddressFormValuesModifierInterface;
use Sylius\Component\Addressing\Model\AddressInterface;
use Webgriffe\SyliusItalianInvoiceableOrderPlugin\Model\ItalianInvoiceableAddressInterface;
use Webmozart\Assert\Assert;

final readonly class AddressFormValuesModifier implements AddressFormValuesModifierInterface
{
    /**
     * @param array<string, mixed> $addressData
     *
     * @return array<string, mixed>
     */
    public function modify(array $addressData, AddressInterface $address): array
    {
        Assert::isInstanceOf(
            $address,
            ItalianInvoiceableAddressInterface::class,
            sprintf(
                'Address must be an instance of %s to modify its form values.',
                ItalianInvoiceableAddressInterface::class,
            ),
        );
        $addressData['billingRecipientType'] = $address->getBillingRecipientType();
        $addressData['taxCode'] = $address->getTaxCode();
        $addressData['vatNumber'] = $address->getVatNumber();
        $addressData['sdiCode'] = $address->getSdiCode();
        $addressData['pecAddress'] = $address->getPecAddress();

        return $addressData;
    }
}
