<?php

declare(strict_types=1);

namespace Webgriffe\SyliusItalianInvoiceableOrderPlugin\Comparator;

use Sylius\Component\Addressing\Comparator\AddressComparatorInterface;
use Sylius\Component\Addressing\Model\AddressInterface;
use Webgriffe\SyliusItalianInvoiceableOrderPlugin\Model\ItalianInvoiceableAddressInterface;

final class ItalianInvoiceableAddressComparatorDecorator implements AddressComparatorInterface
{
    public function __construct(
        private AddressComparatorInterface $defaultAddressComparator,
    ) {
    }

    #[\Override]
    public function equal(AddressInterface $firstAddress, AddressInterface $secondAddress): bool
    {
        $equal = $this->defaultAddressComparator->equal($firstAddress, $secondAddress);
        if (!$firstAddress instanceof ItalianInvoiceableAddressInterface ||
            !$secondAddress instanceof ItalianInvoiceableAddressInterface) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Both addresses provided to "%s" must be of type "%s", but "%s" and "%s" provided.',
                    __METHOD__,
                    ItalianInvoiceableAddressInterface::class,
                    get_debug_type($firstAddress),
                    get_debug_type($secondAddress),
                ),
            );
        }

        return $equal &&
            $this->normalizeInvoiceableAddress($firstAddress) === $this->normalizeInvoiceableAddress($secondAddress);
    }

    private function normalizeInvoiceableAddress(ItalianInvoiceableAddressInterface $address): array
    {
        return array_map(
            function ($value): string {
                return strtolower(trim((string) $value));
            },
            [
               $address->getBillingRecipientType(),
               $address->getTaxCode(),
               $address->getVatNumber(),
               $address->getSdiCode(),
               $address->getPecAddress(),
           ],
        );
    }
}
