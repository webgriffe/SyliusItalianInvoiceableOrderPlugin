<p align="center">
    <a href="https://sylius.com" target="_blank">
        <img src="https://demo.sylius.com/assets/shop/img/logo.png" />
    </a>
</p>
<h1 align="center">Italian Invoiceable Order Plugin</h1>

<p align="center">Sylius plugin which allows Italian merchants to collect invoice data for their orders such as tax code, VAT number, SDI code, etc...</p>



<p align="center"><a href="https://travis-ci.org/webgriffe/SyliusItalianInvoiceableOrderPlugin"><img src="https://travis-ci.org/webgriffe/SyliusItalianInvoiceableOrderPlugin.svg?branch=master" alt="Build Status" /></a></p>

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

7. Run a diff of your Doctrine's migrations and then run it:

   ```bash
   bin/console doctrine:migrations:diff
   bin/console doctrine:migrations:migrate
   ```

8. Add invoiceable address fields to you address form template. In the `templates/bundles/SyliusShopBundle/Common/Form/_address.html.twig` you must add the following:

   ```twig
   {% if type != 'shipping-' %}
       {{ form_row(form.billingRecipientType, sylius_test_form_attribute(type ~ 'billing-recipient-type')) }}
       {{ form_row(form.taxCode, sylius_test_form_attribute(type ~ 'tax-code')) }}
       {{ form_row(form.vatNumber, sylius_test_form_attribute(type ~ 'vat-number')) }}
       {{ form_row(form.sdiCode, sylius_test_form_attribute(type ~ 'sdi-code')) }}
       {{ form_row(form.pecAddress, sylius_test_form_attribute(type ~ 'pec-address')) }}    
   {% endif %}
   ```

   You can put the fields in the order you want but we recommend to surround them with the `{% if type != 'shipping-' %}` check. In this way you'll not show those fields in the shipping address section of the checkout where these fields are not relevant.
   
9. Add invoiceable fields to the address show template for admin and shop. To do so you have to override those templates:

   ```bash
   cp vendor/sylius/sylius/src/Sylius/Bundle/ShopBundle/Resources/views/Common/_address.html.twig templates/bundles/SyliusShopBundle/Common/_address.html.twig
   cp vendor/sylius/sylius/src/Sylius/Bundle/AdminBundle/Resources/views/Common/_address.html.twig templates/bundles/SyliusAdminBundle/Common/_address.html.twig
   ```

   And include the invoiceable address fields template provided by this plugin:
   
   ```twig
   {# templates/bundles/SyliusShopBundle/Common/_address.html.twig #}
   {# and #}
   {# templates/bundles/SyliusAdminBundle/Common/_address.html.twig #}
   <address>
       {# ... #}
       {% include '@WebgriffeSyliusItalianInvoiceableOrderPlugin/Common/_invoiceableAddressInfo.html.twig' %}
       {# ... #}
   </address>
   ```
   
   You can include it in the position you prefer. We recommend to put include the new template under the company information.
   
10. Add invoiceable fields to the address book select data attributes. To do so you have to override the address book select template:

   ```bash
   cp vendor/sylius/sylius/src/Sylius/Bundle/ShopBundle/Resources/views/Checkout/Address/_addressBookSelect.html.twig templates/bundles/SyliusShopBundle/Checkout/Address/_addressBookSelect.html.twig
   ```

   And include the invoiceable fields data attributes template provided by this plugin:

   ```twig
   {% include '@WebgriffeSyliusItalianInvoiceableOrderPlugin/Checkout/Address/_addressBookSelectInvoiceableDataAttributes.html.twig' %}
   ```

   You have to add it in the proper location, just after the other data attributes of the address book select tag. So the whole address book template should look like the following: 

   ```twig
   {# templates/bundles/SyliusShopBundle/Checkout/Address/_addressBookSelect.html.twig #}
   {% if app.user is not empty and app.user.customer is not empty and app.user.customer.addresses|length > 0 %}
       <div class="ui fluid floating dropdown labeled search icon button address-book-select" {{ sylius_test_html_attribute('address-book') }}>
           <i class="book icon"></i>
           <span class="text">{{ 'sylius.ui.select_address_from_book'|trans }}</span>
           <div class="menu">
               {% for address in app.user.customer.addresses %}
                   <div class="item" {{ sylius_test_html_attribute('address-book-item') }}
                        data-id="{{ address.id }}"
                        data-first-name="{{ address.firstName }}"
                        data-last-name="{{ address.lastName }}"
                        data-company="{{ address.company }}"
                        data-street="{{ address.street }}"
                        data-country-code="{{ address.countryCode }}"
                        data-province-code="{{ address.provinceCode }}"
                        data-province-name="{{ address.provinceName }}"
                        data-city="{{ address.city }}"
                        data-postcode="{{ address.postcode }}"
                        data-phone-number="{{ address.phoneNumber }}"
   
                        {% include '@WebgriffeSyliusItalianInvoiceableOrderPlugin/Checkout/Address/_addressBookSelectInvoiceableDataAttributes.html.twig' %}
                   >
                       <strong>{{ address.firstName }} {{ address.lastName }}</strong>, {{ address.street }}, {{ address.city }} {{ address.postcode }}, {{ address.countryCode|sylius_country_name }}
                   </div>
               {% endfor %}
           </div>
       </div>
   {% endif %}
   ```

## Features

Once installed this plugin will allow the users of the shop to enter all the invoicing information needed by an Italian company to properly invoice the order.

This plugin will add the following fields to your address form:

* **Billing recipient type**, which allows the user to specify if the billing address is for a company or for an individual. This field will be required for every address used as the billing address for an order.
* **Tax code**, which in Italy is known as "*Codice Fiscale*". It's, more or less, like the social security number (SSN) in the US but in Italy both companies and individuals have a tax code. This field will be required for Italian individuals and companies and validated according the proper checksum algorithm.
* **VAT Number**, which in Italy is known as "*Partita IVA*". It's another identification number but for companies only not only in Italy but also in the EU. This field will be required for Italian companies and validated according the proper checksum algorithm. This field will be also required for EU companies and validated using the [EU's VIES service](https://ec.europa.eu/taxation_customs/vies/) using the [MyOnlineStore/ViesBundle](MyOnlineStore/ViesBundle).
* **SDI Code**, where SDI stands for "*Sistema Di Interscambio*". It's a code that is needed to be able to properly generate an "electronic invoice" which is mandatory in Italy since January the 1st of 2019. This field will be required for Italian companies and validated according the proper rules of the Italian IRS (called "*Agenzia delle Entrate*" in Italy).
* **PEC address**, where PEC stands for "*Posta Elettronica Certificata*" (the Italian translation of "certified email"). It's like an email address but for a special email type meant to provide a legal equivalent of the traditional mail. See [here](https://en.wikipedia.org/wiki/Certified_email) for more information. This field will not be required even for Italian companies but if entered it must be a valid email address.

This plugin will also require the Sylius's *company* field to be populated if the billing recipient type is set to company.

This plugin also replaces the Sylius's `Sylius\Component\Addressing\Comparator\AddressComparatorInterface` implementation by decorating it and by comparing also invoiceable fields. So different invoiceable address information provided during checkout are saved in the customer address book as new addresses.

This plugin also allow to select an invoiceable address from the address book in the checkout and properly fill the address form with all the invoicing information.

## Contributing

To contribute you need to:

1. Clone this repository into your development environment

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