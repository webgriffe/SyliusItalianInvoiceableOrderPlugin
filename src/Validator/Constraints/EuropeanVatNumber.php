<?php

declare(strict_types=1);

namespace Webgriffe\SyliusItalianInvoiceableOrderPlugin\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class EuropeanVatNumber extends Constraint
{
    public bool $strict = false;

    public string $format = 'NL';

    public string $message = 'webgriffe_sylius_italian_invoiceable_order.address.european_vat_number.valid';

    public function isStrict(): bool
    {
        return $this->strict;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    #[\Override]
    public function validatedBy(): string
    {
        return 'webgriffe_sylius_italian_invoiceable_order.european_vat_number_validator';
    }
}
