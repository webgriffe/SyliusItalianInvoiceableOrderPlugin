<?php

declare(strict_types=1);

namespace Webgriffe\SyliusItalianInvoiceableOrderPlugin\Taxation;

use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Taxation\Applicator\OrderTaxesApplicatorInterface;
use Sylius\Component\Core\Taxation\Strategy\TaxCalculationStrategyInterface;
use Webgriffe\SyliusItalianInvoiceableOrderPlugin\Model\ItalianInvoiceableAddressInterface;
use Webmozart\Assert\Assert;

final class ItalianTaxCalculationStrategy implements TaxCalculationStrategyInterface
{
    /** @var iterable<OrderTaxesApplicatorInterface> */
    private iterable $applicators;

    /**
     * @param iterable<OrderTaxesApplicatorInterface>|iterable $applicators
     */
    public function __construct(
        private string $type,
        iterable $applicators,
        private string $euTaxZoneCode,
    ) {
        Assert::allIsInstanceOf($applicators, OrderTaxesApplicatorInterface::class);

        $this->applicators = $applicators;
    }

    #[\Override]
    public function applyTaxes(OrderInterface $order, ZoneInterface $zone): void
    {
        if ($this->shouldSkipTaxesApplication($order, $zone)) {
            return;
        }

        foreach ($this->applicators as $applicator) {
            $applicator->apply($order, $zone);
        }
    }

    #[\Override]
    public function getType(): string
    {
        return $this->type;
    }

    #[\Override]
    public function supports(OrderInterface $order, ZoneInterface $zone): bool
    {
        /** @var ChannelInterface|\Sylius\Component\Channel\Model\ChannelInterface $channel */
        $channel = $order->getChannel();

        Assert::isInstanceOf($channel, ChannelInterface::class);

        return $channel->getTaxCalculationStrategy() === $this->type;
    }

    private function shouldSkipTaxesApplication(OrderInterface $order, ZoneInterface $zone): bool
    {
        if ($this->euTaxZoneCode === '') {
            //EU tax zone not configured. Behave as if this strategy was not present, that is always apply taxes.
            return false;
        }

        $billingAddress = $order->getBillingAddress();

        Assert::nullOrIsInstanceOf($billingAddress, ItalianInvoiceableAddressInterface::class);

        $companyRecipientType = ItalianInvoiceableAddressInterface::BILLING_RECIPIENT_TYPE_COMPANY;

        //Skip taxes application if we are billing to an EU company (excluding Italy)
        //@todo: can this be generalized by saying that we can skip taxes application whenever we're billing to a
        //  company in the EU that is in a country other than the origin country?
        return ($billingAddress !== null) &&
            ($billingAddress->getBillingRecipientType() === $companyRecipientType) &&
            ($zone->getCode() === $this->euTaxZoneCode) &&
            ($billingAddress->getCountryCode() !== 'IT');
    }
}
