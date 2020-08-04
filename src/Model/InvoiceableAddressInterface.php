<?php

declare(strict_types=1);

namespace Webgriffe\SyliusItalianInvoiceableOrderPlugin\Model;

interface InvoiceableAddressInterface
{
    public const BILLING_RECIPIENT_TYPE_INDIVIDUAL = 'individual';

    public const BILLING_RECIPIENT_TYPE_COMPANY = 'company';

    public function getBillingRecipientType(): ?string;

    public function setBillingRecipientType(?string $type): void;

    public function getTaxCode(): ?string;

    public function setTaxCode(?string $taxCode): void;

    public function getVatNumber(): ?string;

    public function setVatNumber(?string $vatNumber): void;

    public function getSdiCode(): ?string;

    public function setSdiCode(?string $sdiCode): void;

    public function getPecAddress(): ?string;

    public function setPecAddress(?string $pecAddress): void;
}
