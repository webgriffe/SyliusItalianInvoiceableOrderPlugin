<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Context\Ui\Shop\Checkout;

use Behat\Behat\Context\Context;
use Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Page\Shop\Checkout\CompletePageInterface;
use Webmozart\Assert\Assert;

final class CheckoutCompleteContext implements Context
{
    public function __construct(private CompletePageInterface $completePage)
    {
    }

    /**
     * @Then /^I should be notified that the billing recipient type is required for the order$/
     */
    public function iShouldBeNotifiedThatTheBillingRecipientTypeIsRequiredForTheOrder(): void
    {
        Assert::true($this->completePage->hasEmptyBillingRecipientTypeValidationMessage());
    }
}
