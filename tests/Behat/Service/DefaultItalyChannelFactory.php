<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Service;

use Sylius\Component\Addressing\Factory\ZoneFactoryInterface;
use Sylius\Component\Addressing\Model\CountryInterface;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Channel\Factory\ChannelFactoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Test\Services\DefaultChannelFactoryInterface;
use Sylius\Component\Currency\Model\CurrencyInterface;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

class DefaultItalyChannelFactory implements DefaultChannelFactoryInterface
{
    public const DEFAULT_CHANNEL_CODE = 'WEB-IT';

    public const DEFAULT_COUNTRY_CODE = 'IT';

    public const DEFAULT_ZONE_CODE = 'IT';

    public const DEFAULT_CURRENCY_CODE = 'EUR';

    public const DEFAULT_ZONE_NAME = 'Italy';

    public const DEFAULT_CHANNEL_NAME = 'Italy';

    /**
     * @param RepositoryInterface<ChannelInterface> $channelRepository
     * @param RepositoryInterface<CountryInterface> $countryRepository
     * @param RepositoryInterface<CurrencyInterface> $currencyRepository
     * @param RepositoryInterface<LocaleInterface> $localeRepository
     * @param RepositoryInterface<ZoneInterface> $zoneRepository
     * @param FactoryInterface<CountryInterface> $countryFactory
     * @param FactoryInterface<CurrencyInterface> $currencyFactory
     * @param FactoryInterface<LocaleInterface> $localeFactory
     */
    public function __construct(
        private RepositoryInterface $channelRepository,
        private RepositoryInterface $countryRepository,
        private RepositoryInterface $currencyRepository,
        private RepositoryInterface $localeRepository,
        private RepositoryInterface $zoneRepository,
        private ChannelFactoryInterface $channelFactory,
        private FactoryInterface $countryFactory,
        private FactoryInterface $currencyFactory,
        private FactoryInterface $localeFactory,
        private ZoneFactoryInterface $zoneFactory,
        private string $defaultLocaleCode,
    ) {
    }

    public function create(
        ?string $code = null,
        ?string $name = null,
        ?string $currencyCode = null,
        ?string $localeCode = null,
    ): array {
        $currency = $this->provideCurrency($currencyCode);
        $locale = $this->provideLocale($localeCode);

        $channel = $this->createChannel($code ?? self::DEFAULT_CHANNEL_CODE, $name ?? self::DEFAULT_CHANNEL_NAME);
        $channel->addCurrency($currency);
        $channel->setBaseCurrency($currency);
        $channel->addLocale($locale);
        $channel->setDefaultLocale($locale);
        $channel->setTaxCalculationStrategy('order_items_based');

        $defaultData = [
            'channel' => $channel,
            'country' => $this->createCountry(),
            'currency' => $currency,
            'locale' => $locale,
            'zone' => $this->createZone(),
        ];

        $this->channelRepository->add($channel);
        $this->countryRepository->add($defaultData['country']);
        $this->zoneRepository->add($defaultData['zone']);

        return $defaultData;
    }

    private function createChannel(string $code, string $name): ChannelInterface
    {
        /** @var ChannelInterface $channel */
        $channel = $this->channelFactory->createNamed($name);
        $channel->setCode($code);

        return $channel;
    }

    private function createCountry(): CountryInterface
    {
        $country = $this->countryFactory->createNew();
        $country->setCode(self::DEFAULT_COUNTRY_CODE);

        return $country;
    }

    private function provideCurrency(?string $currencyCode = null): CurrencyInterface
    {
        $currencyCode = $currencyCode ?? self::DEFAULT_CURRENCY_CODE;

        $currency = $this->currencyRepository->findOneBy(['code' => $currencyCode]);

        if (null === $currency) {
            /** @var CurrencyInterface $currency */
            $currency = $this->currencyFactory->createNew();
            $currency->setCode($currencyCode);

            $this->currencyRepository->add($currency);
        }

        return $currency;
    }

    private function provideLocale(?string $localeCode = null): LocaleInterface
    {
        $locale = $this->localeRepository->findOneBy(['code' => $this->defaultLocaleCode]);

        if (null === $locale) {
            $locale = $this->localeFactory->createNew();
            $locale->setCode($localeCode ?? $this->defaultLocaleCode);

            $this->localeRepository->add($locale);
        }

        return $locale;
    }

    private function createZone(): ZoneInterface
    {
        $zone = $this->zoneFactory->createWithMembers([self::DEFAULT_ZONE_CODE]);
        $zone->setCode(self::DEFAULT_ZONE_CODE);
        $zone->setName(self::DEFAULT_ZONE_NAME);
        $zone->setType(ZoneInterface::TYPE_COUNTRY);

        return $zone;
    }
}
