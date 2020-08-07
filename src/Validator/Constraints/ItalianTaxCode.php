<?php

declare(strict_types=1);

namespace Webgriffe\SyliusItalianInvoiceableOrderPlugin\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

final class ItalianTaxCode extends Constraint
{
    /** @var string */
    public $message = 'app.italian_tax_code.valid';
}
