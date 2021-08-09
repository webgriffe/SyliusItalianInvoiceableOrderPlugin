<?php

declare(strict_types=1);

namespace Webgriffe\SyliusItalianInvoiceableOrderPlugin\Model;

use Doctrine\ORM\Mapping as ORM;

trait ItalianInvoiceableAddressTrait
{
    /**
     * @var string|null
     *
     * @ORM\Column(name="billing_recipient_type", type="string", nullable=true)
     */
    private $billingRecipientType;

    /**
     * @var string|null
     *
     * @ORM\Column(name="tax_code", type="string", nullable=true)
     */
    private $taxCode;

    /**
     * @var string|null
     *
     * @ORM\Column(name="vat_number", type="string", nullable=true)
     */
    private $vatNumber;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sdi_code", type="string", nullable=true)
     */
    private $sdiCode;

    /**
     * @var string|null
     *
     * @ORM\Column(name="pec_address", type="string", nullable=true)
     */
    private $pecAddress;

    public function getBillingRecipientType(): ?string
    {
        return $this->billingRecipientType;
    }

    public function setBillingRecipientType(?string $billingRecipientType): void
    {
        $this->billingRecipientType = $billingRecipientType;
    }

    public function getTaxCode(): ?string
    {
        return $this->taxCode;
    }

    public function setTaxCode(?string $taxCode): void
    {
        $this->taxCode = $taxCode;
    }

    public function getVatNumber(): ?string
    {
        return $this->vatNumber;
    }

    public function setVatNumber(?string $vatNumber): void
    {
        $this->vatNumber = $vatNumber;
    }

    public function getSdiCode(): ?string
    {
        return $this->sdiCode;
    }

    public function setSdiCode(?string $sdiCode): void
    {
        $this->sdiCode = $sdiCode;
    }

    public function getPecAddress(): ?string
    {
        return $this->pecAddress;
    }

    public function setPecAddress(?string $pecAddress): void
    {
        $this->pecAddress = $pecAddress;
    }

    public static function getBillingRecipientTypes(): array
    {
        return [
            ItalianInvoiceableAddressInterface::BILLING_RECIPIENT_TYPE_INDIVIDUAL,
            ItalianInvoiceableAddressInterface::BILLING_RECIPIENT_TYPE_COMPANY,
        ];
    }

    public function getGroupSequence(): array
    {
        $groupSequence = ['sylius'];
        if ($this->getCountryCode() !== null) {
            $groupSequence[] = $this->getCountryCode();
        }
        if ($this->getBillingRecipientType() !== null) {
            $groupSequence[] = $this->getBillingRecipientType();
            if ($this->getCountryCode() !== null) {
                $groupSequence[] = sprintf('%s-%s', $this->getBillingRecipientType(), $this->getCountryCode());
            }
        }
        return [$groupSequence];
    }
}
