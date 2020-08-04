<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Core\Test\Services\DefaultChannelFactoryInterface;

final class ChannelContext implements Context
{
    /**
     * @var DefaultChannelFactoryInterface
     */
    private $defaultItalyChannelFactory;
    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    public function __construct(
        DefaultChannelFactoryInterface $defaultItalyChannelFactory,
        SharedStorageInterface $sharedStorage
    ) {
        $this->defaultItalyChannelFactory = $defaultItalyChannelFactory;
        $this->sharedStorage = $sharedStorage;
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
}
