<?php

declare(strict_types=1);

namespace Webgriffe\SyliusItalianInvoiceableOrderPlugin\Comparator;

use Sylius\Component\Addressing\Comparator\AddressComparatorInterface;
use Sylius\Component\Addressing\Model\AddressInterface;
use Webgriffe\SyliusItalianInvoiceableOrderPlugin\Model\ItalianInvoiceableAddressInterface;
use Webmozart\Assert\Assert;

final class ItalianInvoiceableAddressComparatorDecorator implements AddressComparatorInterface
{
    /** @var AddressComparatorInterface */
    private $defaultAddressComparator;

    public function __construct(AddressComparatorInterface $defaultAddressComparator)
    {
        $this->defaultAddressComparator = $defaultAddressComparator;
    }

    /**
     * @param AddressInterface&ItalianInvoiceableAddressInterface $firstAddress
     * @param AddressInterface&ItalianInvoiceableAddressInterface $secondAddress
     */
    public function equal(AddressInterface $firstAddress, AddressInterface $secondAddress): bool
    {
        Assert::allIsInstanceOf([$firstAddress, $secondAddress], ItalianInvoiceableAddressInterface::class);
        $equal = $this->defaultAddressComparator->equal($firstAddress, $secondAddress);

        return $equal &&
            $this->normalizeInvoiceableAddress($firstAddress) === $this->normalizeInvoiceableAddress($secondAddress);
    }

    private function normalizeInvoiceableAddress(ItalianInvoiceableAddressInterface $address): array
    {
        return array_map(function ($value) {
            return strtolower(trim((string) $value));
        }, [
            $address->getBillingRecipientType(),
            $address->getTaxCode(),
            $address->getVatNumber(),
            $address->getSdiCode(),
            $address->getPecAddress(),
        ]);
    }
}
