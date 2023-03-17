<?php

declare(strict_types=1);

namespace Webgriffe\SyliusItalianInvoiceableOrderPlugin\Validator\Constraints;

use DragonBe\Vies\Vies;
use DragonBe\Vies\ViesException;
use DragonBe\Vies\ViesServiceException;
use Sandwich\ViesBundle\Validator\Constraint\VatNumber;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Webgriffe\SyliusItalianInvoiceableOrderPlugin\Vies\HeartBeat;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class VatNumberValidator extends ConstraintValidator
{
    public function __construct(private Vies $viesApi)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!is_string($value) || '' === $value) {
            return;
        }

        if (!$constraint instanceof VatNumber) {
            return;
        }

        $this->viesApi->setHeartBeat(new HeartBeat(Vies::VIES_DOMAIN, Vies::VIES_PORT));

        if (!$this->viesApi->getHeartBeat()->isAlive()) {
            //VIES service is not available
            return;
        }

        $format = $constraint->getFormat();
        $isValid = false;

        try {
            $isValid = $this->viesApi->validateVat($format, str_replace($format, '', $value))->isValid();
        } catch (ViesServiceException $exception) {
            //There is probably a temporary problem with back-end VIES service
            return;
        } catch (ViesException $exception) {
        }

        if ($isValid) {
            return;
        }

        $this->context->addViolation($constraint->message, ['%format%' => $format]);
    }
}
