<?php

declare(strict_types=1);

namespace Webgriffe\SyliusItalianInvoiceableOrderPlugin\Model;

use Webmozart\Assert\Assert;

trait ItalianInvoiceableOrderTrait
{
    public function getBillingRecipientType(): ?string
    {
        if ($this->getBillingAddress() === null) {
            return null;
        }
        /** @var ItalianInvoiceableAddressInterface|null $billingAddress */
        $billingAddress = $this->getBillingAddress();
        Assert::isInstanceOf($billingAddress, ItalianInvoiceableAddressInterface::class);

        return $billingAddress->getBillingRecipientType();
    }
}
