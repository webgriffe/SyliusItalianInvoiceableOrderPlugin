<?php

declare(strict_types=1);

namespace Webgriffe\SyliusItalianInvoiceableOrderPlugin\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ItalianVatNumber extends Constraint
{
    /** @var string */
    public $message = 'app.italian_vat_number.valid';
}
