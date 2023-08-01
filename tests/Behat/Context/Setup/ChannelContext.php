<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Addressing\Model\CountryInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Test\Services\DefaultChannelFactoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Intl\Countries;
use Webmozart\Assert\Assert;

final class ChannelContext implements Context
{
    /**
     * @param FactoryInterface<CountryInterface> $countryFactory
     * @param RepositoryInterface<CountryInterface> $countryRepository
     */
    public function __construct(
        private DefaultChannelFactoryInterface $defaultChannelFactory,
        private DefaultChannelFactoryInterface $defaultItalyChannelFactory,
        private SharedStorageInterface $sharedStorage,
        private FactoryInterface $countryFactory,
        private ChannelRepositoryInterface $channelRepository,
        private RepositoryInterface $countryRepository,
        private array $availableTaxCalculationStrategies
    ) {
    }

    /**
     * @Given /^the store operates on a single channel in "Italy"$/
     */
    public function theStoreOperatesOnASingleChannelIn(): void
    {
        $defaultData = $this->defaultItalyChannelFactory->create();

        $this->sharedStorage->setClipboard($defaultData);
        $this->sharedStorage->set('channel', $defaultData['channel']);
    }

    /**
     * @Given /^the store operates on a single channel worldwide$/
     */
    public function theStoreOperatesOnASingleChannelWorldwide(): void
    {
        $defaultData = $this->defaultChannelFactory->create();
        /** @var ChannelInterface $channel */
        $channel = $defaultData['channel'];
        foreach (Countries::getCountryCodes() as $countryCode) {
            $country = $this->countryFactory->createNew();
            $country->setCode($countryCode);
            $country->setEnabled(true);
            $this->countryRepository->add($country);
            $channel->addCountry($country);
        }

        $this->channelRepository->add($channel);

        $this->sharedStorage->setClipboard($defaultData);
        $this->sharedStorage->set('channel', $channel);
    }

    /**
     * @Given /^(its) tax calculation strategy is "([^"]*)"$/
     */
    public function itsTaxCalculationStrategyIs(ChannelInterface $channel, string $taxCalculationStrategy): void
    {
        $strategies = array_flip($this->availableTaxCalculationStrategies);
        Assert::keyExists($strategies, $taxCalculationStrategy);
        $channel->setTaxCalculationStrategy($strategies[$taxCalculationStrategy]);
        $this->channelRepository->add($channel);
    }
}
