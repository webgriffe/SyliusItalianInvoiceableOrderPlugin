<?php

declare(strict_types=1);

namespace Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Page\Shop;

use FriendsOfBehat\PageObjectExtension\Page\SymfonyPage;
use Tests\Webgriffe\SyliusItalianInvoiceableOrderPlugin\Behat\Page\Shop\WelcomePageInterface;

class DynamicWelcomePage extends SymfonyPage implements WelcomePageInterface
{
    /**
     * {@inheritdoc}
     */
    public function getGreeting(): string
    {
        return $this->getSession()->getPage()->waitFor(3, function (): string {
            $greeting = $this->getElement('greeting')->getText();

            if ('Loading...' === $greeting) {
                return '';
            }

            return $greeting;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteName(): string
    {
        return 'webgriffe_sylius_italian_invoiceable_order_dynamic_welcome';
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
