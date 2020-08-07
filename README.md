<p align="center">
    <a href="https://sylius.com" target="_blank">
        <img src="https://demo.sylius.com/assets/shop/img/logo.png" />
    </a>
</p>
<h1 align="center">Italian Invoiceable Order Plugin</h1>

<p align="center">Sylius plugin which allows Italian merchants to collect invoice data for their orders.</p>

## Installation

1. Require the plugin:

   ```bash
   composer require webgriffe/sylius-italian-invoiceable-order-plugin
   ```

2. Add bundles to `config/bundles.php` file:

   ```php
       Sandwich\ViesBundle\SandwichViesBundle::class => ['all' => true],
       Webgriffe\SyliusItalianInvoiceableOrderPlugin\WebgriffeSyliusItalianInvoiceableOrderPlugin::class => ['all' => true],
   ```

3. Your `Address` entity must implement the `Webgriffe\SyliusItalianInvoiceableOrderPlugin\Model\ItalianInvoiceableAddressInterface` and the `Symfony\Component\Validator\GroupSequenceProviderInterface`. You can use the `Webgriffe\SyliusItalianInvoiceableOrderPlugin\Model\ItalianInvoiceableAddressTrait` as implementation for both interfaces.

4. Your `Order` entity must implement the `Webgriffe\SyliusItalianInvoiceableOrderPlugin\Model\ItalianInvoiceableOrderInterface`. You can use the `Webgriffe\SyliusItalianInvoiceableOrderPlugin\Model\ItalianInvoiceableOrderTrait` as default implementation for the interface.

5. You need to import the `Address` and `Order` validator configuration into your project by copying the configuration files provided by this plugin:

   ```bash
   mkdir -p config/validator/
   cp vendor/webgriffe/sylius-italian-invoiceable-order-plugin/tests/Application/config/validator/Address.xml config/validator/
   cp vendor/webgriffe/sylius-italian-invoiceable-order-plugin/tests/Application/config/validator/Order.xml config/validator/
   ```

   Or by merging the configuration into your existing `Address` and `Order` validator configuration.

6. To properly enable group sequence validation of your Address entity you must set the `Default` validation group instead of the `sylius` validation group:

   ```yaml
   # config/services.yaml
   parameters:
       # ...
       sylius.form.type.address.validation_groups: ['Default']
   ```

   For more information see [here](https://symfony.com/doc/current/validation/sequence_provider.html).

7. You need to run a diff of your Doctrine's migrations and then run it:

   ```bash
   bin/console doctrine:migrations:diff
   bin/console doctrine:migrations:migrate
   ```

8. You need to add invoiceable address fields to you address form template. In the `templates/bundles/SyliusShopBundle/Common/Form/_address.html.twig` you must add the following:

   ```twig
   {% if type != 'shipping-' %}
       {{ form_row(form.billingRecipientType, sylius_test_form_attribute(type ~ 'billing-recipient-type')) }}
       {{ form_row(form.taxCode, sylius_test_form_attribute(type ~ 'tax-code')) }}
       {{ form_row(form.vatNumber, sylius_test_form_attribute(type ~ 'vat-number')) }}
       {{ form_row(form.sdiCode, sylius_test_form_attribute(type ~ 'sdi-code')) }}
       {{ form_row(form.pecAddress, sylius_test_form_attribute(type ~ 'pec-address')) }}    
   {% endif %}
   ```

   You can put the fields in the order you want but we recommend to sorround them with the `{% if type != 'shipping-' %}` check. In this way you'll show those fields only in the billing address section of the checkout.

## Contributing

To contribute you need to:

1. Clone this repository into you development environment

2. Create `tests/Application/.env.local` and `tests/Application/.env.test.local` files to customize env vars according to your specific development environment (for example the `DATABASE_URL` variable).

3. Then, from the plugin's root directory, run the following commands:

   ```bash
   (cd tests/Application && yarn install)
   (cd tests/Application && yarn build)
   (cd tests/Application && bin/console assets:install public)
   (cd tests/Application && bin/console doctrine:database:create)
   (cd tests/Application && bin/console doctrine:schema:create)
   (cd tests/Application && bin/console sylius:fixtures:load)
   (cd tests/Application && symfony server:start -d) # Requires Symfony CLI (https://symfony.com/download)
   ```

4. Now at http://localhost:8080/ you have a full Sylius testing application which runs the plugin.

### Running plugin tests

After your changes you must ensure that the tests are still passing.

First setup your test database:

```bash
(cd tests/Application && bin/console -e test doctrine:database:create)
(cd tests/Application && bin/console -e test doctrine:schema:create)
```

The current CI suite runs the following tests:

  - PHPUnit

    ```bash
    vendor/bin/phpunit
    ```

  - PHPSpec

    ```bash
    vendor/bin/phpspec run
    ```

  - Behat (non-JS scenarios)

    ```bash
    vendor/bin/behat --strict --tags="~@javascript"
    ```

  - Behat (JS scenarios)

    1. [Install Symfony CLI command](https://symfony.com/download).

    2. Start Headless Chrome:
    
      ```bash
      google-chrome-stable --enable-automation --disable-background-networking --no-default-browser-check --no-first-run --disable-popup-blocking --disable-default-apps --allow-insecure-localhost --disable-translate --disable-extensions --no-sandbox --enable-features=Metal --headless --remote-debugging-port=9222 --window-size=2880,1800 --proxy-server='direct://' --proxy-bypass-list='*' http://127.0.0.1
      ```
    
    3. Install SSL certificates (only once needed) and run test application's webserver on `127.0.0.1:8080`:
    
      ```bash
      symfony server:ca:install
      APP_ENV=test symfony server:start --port=8080 --dir=tests/Application/public --daemon
      ```
    
    4. Run Behat:
    
      ```bash
      vendor/bin/behat --strict --tags="@javascript"
      ```
    
  - Static Analysis
  
    - Psalm
    
      ```bash
      vendor/bin/psalm
      ```
      
    - PHPStan
    
      ```bash
      vendor/bin/phpstan analyse -c phpstan.neon -l max src/  
      ```

  - Coding Standard
  
    ```bash
    vendor/bin/ecs check src
    ```

## License

This library is under the MIT license. See the complete license in the LICENSE file.

## Credits

Developed by [Webgriffe®](http://www.webgriffe.com/).