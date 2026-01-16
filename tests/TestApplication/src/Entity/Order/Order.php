<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Entity\Order;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\Order as BaseOrder;
use Webgriffe\SyliusItalianInvoiceableOrderPlugin\Model\ItalianInvoiceableOrderInterface;
use Webgriffe\SyliusItalianInvoiceableOrderPlugin\Model\ItalianInvoiceableOrderTrait;

#[ORM\Entity]
#[ORM\Table(name: 'sylius_order')]
class Order extends BaseOrder implements ItalianInvoiceableOrderInterface
{
    use ItalianInvoiceableOrderTrait;
}
