<?php

declare(strict_types=1);

namespace Webgriffe\SyliusItalianInvoiceableOrderPlugin\Validator\Constraints;

use DragonBe\Vies\Vies;
use DragonBe\Vies\ViesException;
use DragonBe\Vies\ViesServiceException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class EuropeanVatNumberValidator extends ConstraintValidator
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct(
        private readonly Vies $viesApi,
        private readonly LoggerInterface $logger,
    ) {
    }

    #[\Override]
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!is_string($value) || $value === '') {
            return;
        }
        if (!$constraint instanceof EuropeanVatNumber) {
            return;
        }

        if (!$this->viesApi->getHeartBeat()->isAlive()) {
            if ($constraint->isStrict()) {
                $this->context->addViolation('webgriffe_sylius_italian_invoiceable_order.address.european_vat_number.vies_not_alive');
            }

            return;
        }

        $format = $constraint->getFormat();

        try {
            $isValidEuropeanVatNumber = $this->viesApi->validateVat(
                $format,
                str_replace($format, '', $value),
            )->isValid();
        } catch (ViesServiceException|ViesException $exception) {
            $this->logger->warning($exception->getMessage());
            if ($constraint->isStrict()) {
                $this->context->addViolation('webgriffe_sylius_italian_invoiceable_order.address.european_vat_number.vies_not_alive');
            }

            return;
        }
        if ($isValidEuropeanVatNumber) {
            return;
        }

        $this->context->addViolation($constraint->message, ['%format%' => $format]);
    }
}
