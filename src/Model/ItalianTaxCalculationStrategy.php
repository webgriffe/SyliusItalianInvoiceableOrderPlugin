<?php

declare(strict_types=1);

namespace Webgriffe\SyliusItalianInvoiceableOrderPlugin\Model;

use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Taxation\Applicator\OrderTaxesApplicatorInterface;
use Sylius\Component\Core\Taxation\Strategy\TaxCalculationStrategyInterface;
use Webmozart\Assert\Assert;

final class ItalianTaxCalculationStrategy implements TaxCalculationStrategyInterface
{
    /** @var string $type */
    private $type;

    /** @var array|OrderTaxesApplicatorInterface[] */
    private $applicators;

    /** @var string $euTaxZoneCode */
    private $euTaxZoneCode;

    /**
     * @param array|OrderTaxesApplicatorInterface[] $applicators
     */
    public function __construct(string $type, array $applicators, string $euTaxZoneCode)
    {
        $this->assertApplicatorsHaveCorrectType($applicators);

        $this->type = $type;
        $this->applicators = $applicators;
        $this->euTaxZoneCode = $euTaxZoneCode;
    }

    public function applyTaxes(OrderInterface $order, ZoneInterface $zone): void
    {
        if ($this->shouldSkipTaxesApplication($order, $zone)) {
            return;
        }

        foreach ($this->applicators as $applicator) {
            $applicator->apply($order, $zone);
        }
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function supports(OrderInterface $order, ZoneInterface $zone): bool
    {
        /** @var ChannelInterface|\Sylius\Component\Channel\Model\ChannelInterface $channel */
        $channel = $order->getChannel();

        Assert::isInstanceOf($channel, ChannelInterface::class);

        return $channel->getTaxCalculationStrategy() === $this->type;
    }

    /**
     * @param array|OrderTaxesApplicatorInterface[] $applicators
     */
    private function assertApplicatorsHaveCorrectType(array $applicators): void
    {
        Assert::allIsInstanceOf(
            $applicators,
            OrderTaxesApplicatorInterface::class,
            'Order taxes applicator should have type "%2$s". Got: %s'
        );
    }

    private function shouldSkipTaxesApplication(OrderInterface $order, ZoneInterface $zone): bool
    {
        /** @var AddressInterface|null $billingAddress */
        $billingAddress = $order->getBillingAddress();

        Assert::isInstanceOf($billingAddress, ItalianInvoiceableAddressInterface::class);

        $companyRecipientType = ItalianInvoiceableAddressInterface::BILLING_RECIPIENT_TYPE_COMPANY;

        return ($billingAddress !== null) &&
            ($billingAddress->getBillingRecipientType() === $companyRecipientType) &&
            ($zone->getCode() === $this->euTaxZoneCode) &&
            ($billingAddress->getCountryCode() !== 'IT');
    }
}
