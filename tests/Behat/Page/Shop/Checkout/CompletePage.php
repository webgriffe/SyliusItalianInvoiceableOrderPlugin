<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Page\Shop\Checkout;

use Sylius\Behat\Page\Shop\Checkout\CompletePage as BaseCompletePage;

class CompletePage extends BaseCompletePage implements CompletePageInterface
{
    public function hasEmptyBillingRecipientTypeValidationMessage(): bool
    {
        $message = 'A billing recipient type (company or individual) should be specified for the billing address of' .
            ' this order. Please go back to the checkout addressing step and specify a billing recipient type for ' .
            'the billing address.';

        return $this->getElement('validation_errors')->getText() === $message;
    }
}
