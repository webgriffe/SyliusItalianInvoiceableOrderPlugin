<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Page\Shop;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;
use Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Page\Shop\WelcomePageInterface;

class StaticWelcomePage extends SymfonyPage implements WelcomePageInterface
{
    /**
     * {@inheritdoc}
     */
    public function getGreeting(): string
    {
        return $this->getElement('greeting')->getText();
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteName(): string
    {
        return 'webgriffe_sylius_italian_invoiceable_order_static_welcome';
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefinedElements(): array
    {
        return array_merge(parent::getDefinedElements(), [
            'greeting' => '#greeting',
        ]);
    }
}
