<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\Address as BaseAddress;
use Webgriffe\SyliusItalianInvoiceableOrderPlugin\Model\ItalianInvoiceableAddressInterface;
use Webgriffe\SyliusItalianInvoiceableOrderPlugin\Model\ItalianInvoiceableAddressTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_address")
 */
final class Address extends BaseAddress implements ItalianInvoiceableAddressInterface
{
    use ItalianInvoiceableAddressTrait;
}
