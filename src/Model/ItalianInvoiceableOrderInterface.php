<?php

declare(strict_types=1);

namespace Webgriffe\SyliusItalianInvoiceableOrderPlugin\Model;

/**
 * @psalm-api
 */
interface ItalianInvoiceableOrderInterface
{
    public function getBillingRecipientType(): ?string;
}
