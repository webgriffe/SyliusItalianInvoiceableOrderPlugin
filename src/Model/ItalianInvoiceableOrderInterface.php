<?php

declare(strict_types=1);

namespace Webgriffe\SyliusItalianInvoiceableOrderPlugin\Model;

interface ItalianInvoiceableOrderInterface
{
    public function getBillingRecipientType(): ?string;
}
