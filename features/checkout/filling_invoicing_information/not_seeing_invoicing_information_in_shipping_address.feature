@filling_invoicing_information
Feature: Not seeing invoicing information in shipping address
    In order to quickly address my order in the checkout
    As a Guest
    I don't want to see any invoicing related field in the shipping address form

    Background:
        Given the store operates on a single channel in "United States"
        And the store ships everywhere for free
        And the store has a product "PHP T-Shirt" priced at "$19.99"
        And I added product "PHP T-Shirt" to the cart
        And I am at the checkout addressing step

    @ui @javascript
    Scenario: Not seeing invoicing information in shipping address
        When I specify the email as "jon.snow@example.com"
        And I specify the billing address for the individual "Jon Snow" - "Sunset Boulevard", "90210", "Los Angeles" - "United States"
        And I specify a valid billing individual tax code
        And I want to ship to a different shipping address
        Then I should not see any invoicing related field in the shipping address form
