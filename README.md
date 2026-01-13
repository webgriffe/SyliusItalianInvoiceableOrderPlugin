<p align="center">
    <a href="https://www.webgriffe.com" target="_blank">
        <img src="https://sylius.com/wp-content/uploads/2018/08/webgriffe_logo.png" height="120" />
    </a>
</p>
<h1 align="center">Italian Invoiceable Order Plugin</h1>

<p align="center"><a href="https://sylius.com/plugins/" target="_blank"><img src="https://sylius.com/assets/badge-approved-by-sylius.png" width="200"></a></p>
<p align="center">Sylius plugin which allows Italian merchants to collect invoice data for their orders such as tax code, VAT number, SDI code, etc... as well as allowing the merchant to only apply taxes to those customers that can (and must) pay taxes in advance.</p>
<p align="center"><a href="https://github.com/webgriffe/SyliusItalianInvoiceableOrderPlugin/actions"><img src="https://github.com/webgriffe/SyliusItalianInvoiceableOrderPlugin/workflows/Build/badge.svg" alt="Build Status" /></a></p>

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

3. By default, the parameter `app.taxation.eu_zone_code` is set to "EU", as it must be the code of a zone representing the EU. This is used to determine if an order is invoiced to a company within the EU or not. Please change this parameter according to your Sylius's zone configuration if needed:

   ```yaml
   # config/services.yaml
   parameters:
       app.taxation.eu_zone_code: 'EU' # Change it if needed
   ```

4. Your `Address` entity must implement the `Webgriffe\SyliusItalianInvoiceableOrderPlugin\Model\ItalianInvoiceableAddressInterface` and the `Symfony\Component\Validator\GroupSequenceProviderInterface`. You can use the `Webgriffe\SyliusItalianInvoiceableOrderPlugin\Model\ItalianInvoiceableAddressTrait` as implementation for both interfaces.

5. Your `Order` entity must implement the `Webgriffe\SyliusItalianInvoiceableOrderPlugin\Model\ItalianInvoiceableOrderInterface`. You can use the `Webgriffe\SyliusItalianInvoiceableOrderPlugin\Model\ItalianInvoiceableOrderTrait` as default implementation for the interface.

6. You need to import the `Address` and `Order` validator configuration into your project by copying the configuration files provided by this plugin:

   ```bash
   mkdir -p config/validator/
   cp vendor/webgriffe/sylius-italian-invoiceable-order-plugin/tests/TestApplication/config/validator/Address.xml config/validator/
   cp vendor/webgriffe/sylius-italian-invoiceable-order-plugin/tests/TestApplication/config/validator/Order.xml config/validator/
   ```

   Or by merging the configuration into your existing `Address` and `Order` validator configuration.

7. Configure Sylius to use the `Italian tax calculation` tax calculation strategy.

8. To properly enable group sequence validation of your Address entity you must set the `Default` validation group instead of the `sylius` validation group:

   ```yaml
   # config/services.yaml
   parameters:
       # ...
       sylius.form.type.address.validation_groups: ['Default']
   ```

   For more information see [here](https://symfony.com/doc/current/validation/sequence_provider.html).

9. Run migration

   ```bash
   vendor/bin/console cache:clear
   vendor/bin/console doctrine:migrations:migrate
   ```


10. Add invoiceable fields to the address show template for admin. To do so you have to override this template:

    ```bash
    vendor/sylius/sylius/src/Sylius/Bundle/AdminBundle/templates/shared/helper/address.html.twig
    ```
   
    by copying directly our implementation provided in the plugin:

    ```bash
    cp tests/TestApplication/templates/bundles/SyliusAdminBundle/shared/helper/address.html.twig templates/bundles/SyliusAdminBundle/shared/helper/address.html.twig
    ```
    
    or by copying the original template and adding the invoiceable fields by yourself. In this case your template should look like the following:
    
    ```twig
    {% macro address(address) %}
        <address>
            {% include '@WebgriffeSyliusItalianInvoiceableOrderPlugin/shared/address/billingAddressInfo.html.twig' with { address } only %}
            {{ address.phoneNumber }}<br/>
            {{ address.street }}<br/>
            {{ address.city }}<br/>
            {% if address|sylius_province_name is not empty %}
                {{ address|sylius_province_name }}<br/>
            {% endif %}
            {{ address.countryCode|sylius_country_name|upper }} {{ address.postcode }}
        </address>
    {% endmacro %}
    ```

## Features

Once installed this plugin will allow the users of the shop to enter all the invoicing information needed by an Italian company to properly invoice the order.
In addition, this plugin checks the billing data of the order and uses them to decide whether the customer has to pay taxes or not.

This plugin will add the following fields to your address form:

* **Billing recipient type**, which allows the user to specify if the billing address is for a company or for an individual. This field will be required for every address used as the billing address for an order.
* **Tax code**, which in Italy is known as "*Codice Fiscale*". It's, more or less, like the social security number (SSN) in the US but in Italy both companies and individuals have a tax code. This field will be required for Italian individuals and companies and validated according the proper checksum algorithm.
* **VAT Number**, which in Italy is known as "*Partita IVA*". It's another identification number but for companies only not only in Italy but also in the EU. This field will be required for Italian companies and validated according the proper checksum algorithm. This field will be also required for EU companies and validated using the [EU's VIES service](https://ec.europa.eu/taxation_customs/vies/) using the [MyOnlineStore/ViesBundle](MyOnlineStore/ViesBundle).
* **SDI Code**, where SDI stands for "*Sistema Di Interscambio*". It's a code that is needed to be able to properly generate an "electronic invoice" which is mandatory in Italy since January the 1st of 2019. This field will be required for Italian companies and validated according the proper rules of the Italian IRS (called "*Agenzia delle Entrate*" in Italy).
* **PEC address**, where PEC stands for "*Posta Elettronica Certificata*" (the Italian translation of "certified email"). It's like an email address but for a special email type meant to provide a legal equivalent of the traditional mail. See [here](https://en.wikipedia.org/wiki/Certified_email) for more information. This field will not be required even for Italian companies, but if entered it must be a valid email address.

This plugin will also require the Sylius's *company* field to be populated if the billing recipient type is set to company.

This plugin also replaces the Sylius's `Sylius\Component\Addressing\Comparator\AddressComparatorInterface` implementation by decorating it and by comparing also invoiceable fields. So different invoiceable address information provided during checkout are saved in the customer address book as new addresses.

This plugin also allows to select an invoiceable address from the address book in the checkout and properly fill the address form with all the invoicing information.

## Contributing

To contribute you need to:

1. Clone this repository into you development environment and go to the plugin's root directory,

2. Then, from the plugin's root directory, run the following commands:

   ```bash
   composer install
   ```

3. Copy `tests/TestApplication/.env` in `tests/TestApplication/.env.local` and set configuration specific for your development environment.

4. Run docker (create a `compose.override.yml` if you need to customize services):

    ```bash
    docker-compose up -d
    ```

4. Then, from the plugin's root directory, run the following commands:

    ```bash
    composer test-app-init
    ```

5. Run your local server:

      ```bash
      symfony server:ca:install
      symfony server:start -d
      ```

6. Now at http://localhost:8080/ you have a full Sylius testing application which runs the plugin

### Testing

After your changes you must ensure that the tests are still passing.

First setup your test database:

```bash
    APP_ENV=test vendor/bin/console doctrine:database:create
    APP_ENV=test vendor/bin/console doctrine:migrations:migrate -n
    # Optionally load data fixtures
    APP_ENV=test vendor/bin/console sylius:fixtures:load -n
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
      APP_ENV=test symfony server:start --port=8080 --dir=tests/TestApplication/public --daemon
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
