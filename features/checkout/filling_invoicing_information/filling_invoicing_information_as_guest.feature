@filling_invoicing_information
Feature: Filling invoicing information for an order
    In order to have my order properly invoiced
    As a Guest
    I want to be able to fill invoicing details

    Background:
        Given the store operates on a single channel in "United States"
        And the store ships everywhere for free
        And the store has a product "PHP T-Shirt" priced at "$19.99"
        And I added product "PHP T-Shirt" to the cart
        And I am at the checkout addressing step

    @ui
    Scenario: Filling invoicing information as individual
        When I specify the email as "jon.snow@example.com"
        And I specify the billing address for the individual "Jon Snow" - "Sunset Boulevard", "90210", "Los Angeles" - "United States"
        And I specify a valid billing individual tax code
        And I complete the addressing step
        Then I should be on the checkout shipping step

    @ui
    Scenario: Filling invoicing information as company
        When I specify the email as "jon.snow@example.com"
        And I specify the billing address for the company "GoT Inc." - "Jon Snow" - "Sunset Boulevard", "90210", "Los Angeles" - "United States"
        And I specify a valid billing VAT number
        And I complete the addressing step
        Then I should be on the checkout shipping step

    @ui
    Scenario: Filling invoicing information with different shipping address
        When I specify the email as "jon.snow@example.com"
        And I specify the billing address for the individual "Jon Snow" - "Sunset Boulevard", "90210", "Los Angeles" - "United States"
        And I specify the shipping address as "New York", "Times Square", "12345", "United States" for "Jon Snow"
        And I complete the addressing step
        Then I should be on the checkout shipping step
