<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Sylius\Component\Addressing\Factory\ZoneFactoryInterface;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Intl\Countries;

final class ZoneContext implements Context
{
    /** @var ZoneFactoryInterface */
    private $zoneFactory;

    /** @var RepositoryInterface */
    private $zoneRepository;

    public function __construct(ZoneFactoryInterface $zoneFactory, RepositoryInterface $zoneRepository)
    {
        $this->zoneFactory = $zoneFactory;
        $this->zoneRepository = $zoneRepository;
    }

    /**
     * @Given /^there is a (tax|shipping) zone "([^"]*)" containing all European Union countries$/
     */
    public function thereIsATaxZoneContainingAllEuropeanUnionCountries(string $scope, string $name): void
    {
        $this->createZone($this->getEuropeanCountries(), $name, $scope);
    }

    /**
     * @Given /^there is a (tax|shipping) zone "([^"]*)" containing all countries outside the European Union$/
     */
    public function thereIsATaxZoneContainingAllCountriesOutsideTheEuropeanUnion(string $scope, string $name): void
    {
        $this->createZone(array_diff(Countries::getCountryCodes(), $this->getEuropeanCountries()), $name, $scope);
    }

    /**
     * @Given /^there is a (tax|shipping) zone "([^"]*)" containing all countries of the world$/
     */
    public function thereIsAShippingZoneContainingAllCountriesOfTheWorld(string $scope, string $name)
    {
        $this->createZone(Countries::getCountryCodes(), $name, $scope);
    }

    private function getEuropeanCountries(): array
    {
        return [
            'AT',
            'BE',
            'BG',
            'CY',
            'CZ',
            'DE',
            'DK',
            'EE',
            'ES',
            'FI',
            'FR',
            'GR',
            'HU',
            'HR',
            'IE',
            'IT',
            'LT',
            'LU',
            'LV',
            'MT',
            'NL',
            'PL',
            'PT',
            'RO',
            'SE',
            'SI',
            'SK'
        ];
    }

    private function createZone(array $membersCodes, string $name, string $scope): void
    {
        $zone = $this->zoneFactory->createWithMembers($membersCodes);
        $zone->setType(ZoneInterface::TYPE_COUNTRY);
        $zone->setCode(StringInflector::nameToCode($name));
        $zone->setName($name);
        $zone->setScope($scope);

        $this->zoneRepository->add($zone);
    }
}
