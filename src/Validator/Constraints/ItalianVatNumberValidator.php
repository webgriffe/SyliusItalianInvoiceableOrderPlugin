<?php

declare(strict_types=1);

namespace Webgriffe\SyliusItalianInvoiceableOrderPlugin\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class ItalianVatNumberValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ItalianVatNumber) {
            throw new UnexpectedTypeException($constraint, ItalianVatNumber::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (!self::isValidItalianVatNumber($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }

    public static function isValidItalianVatNumber(string $value): bool
    {
        if ($value === '') {
            return false;
        }

        if (strlen($value) !== 11) {
            return false;
        }

        /** @phpstan-ignore-next-line */
        if (!preg_match('/^\d+$/', $value)) {
            return false;
        }

        $first = 0;
        for ($i = 0; $i <= 9; $i += 2) {
            $first += ord($value[$i]) - ord('0');
        }

        for ($i = 1; $i <= 9; $i += 2) {
            $second = 2 * (ord($value[$i]) - ord('0'));

            if ($second > 9) {
                $second = $second - 9;
            }
            $first += $second;
        }

        return !((10 - $first % 10) % 10 !== ord($value[10]) - ord('0'));
    }
}
