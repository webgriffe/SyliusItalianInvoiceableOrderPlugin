<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Service;

use Sylius\Component\Addressing\Factory\ZoneFactoryInterface;
use Sylius\Component\Addressing\Model\CountryInterface;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Addressing\Repository\CountryRepositoryInterface;
use Sylius\Component\Addressing\Repository\ZoneRepositoryInterface;
use Sylius\Component\Channel\Factory\ChannelFactoryInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Test\Services\DefaultChannelFactoryInterface;
use Sylius\Component\Currency\Model\CurrencyInterface;
use Sylius\Component\Currency\Repository\CurrencyRepositoryInterface;
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

    public function __construct(
        private ChannelRepositoryInterface $channelRepository,
        private CountryRepositoryInterface $countryRepository,
        private CurrencyRepositoryInterface $currencyRepository,
        private RepositoryInterface $localeRepository,
        private ZoneRepositoryInterface $zoneRepository,
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
        /** @var CountryInterface $country */
        $country = $this->countryFactory->createNew();
        $country->setCode(self::DEFAULT_COUNTRY_CODE);

        return $country;
    }

    private function provideCurrency(?string $currencyCode = null): CurrencyInterface
    {
        $currencyCode = $currencyCode ?? self::DEFAULT_CURRENCY_CODE;

        /** @var CurrencyInterface|null $currency */
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
        /** @var LocaleInterface|null $locale */
        $locale = $this->localeRepository->findOneBy(['code' => $this->defaultLocaleCode]);
        if (null === $locale) {
            /** @var LocaleInterface $locale */
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
