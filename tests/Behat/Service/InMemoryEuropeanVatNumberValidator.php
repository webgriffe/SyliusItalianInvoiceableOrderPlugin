<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Service;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Webgriffe\SyliusItalianInvoiceableOrderPlugin\Validator\Constraints\EuropeanVatNumber;

final class InMemoryEuropeanVatNumberValidator extends ConstraintValidator
{
    public static bool $isValid = true;

    #[\Override]
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!is_string($value) || $value === '') {
            return;
        }

        if (!$constraint instanceof EuropeanVatNumber) {
            return;
        }
        if (self::$isValid) {
            return;
        }

        $format = $constraint->getFormat();
        $this->context->addViolation($constraint->message, ['%format%' => $format]);
    }
}
