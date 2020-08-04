<?php

declare(strict_types=1);

namespace Webgriffe\SyliusItalianInvoiceableOrderPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class WebgriffeSyliusItalianInvoiceableOrderPlugin extends Bundle
{
    use SyliusPluginTrait;
}
