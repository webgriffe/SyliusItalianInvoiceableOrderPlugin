<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Addressing\Model\CountryInterface;
use Sylius\Component\Addressing\Repository\CountryRepositoryInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Test\Services\DefaultChannelFactoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\Intl\Countries;

final class ChannelContext implements Context
{
    public function __construct(
        private DefaultChannelFactoryInterface $defaultChannelFactory,
        private DefaultChannelFactoryInterface $defaultItalyChannelFactory,
        private SharedStorageInterface $sharedStorage,
        private FactoryInterface $countryFactory,
        private ChannelRepositoryInterface $channelRepository,
        private CountryRepositoryInterface $countryRepository,
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
            /** @var CountryInterface $country */
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
}
