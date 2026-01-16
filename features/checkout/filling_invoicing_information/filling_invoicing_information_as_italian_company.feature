@filling_invoicing_information
Feature: Filling invoicing information as an italian company
    In order to have my order properly invoiced in the italian SDI (Sistema di Interscambio)
    As a Guest
    I want to be able to fill italian invoicing details for my company

    Background:
        Given the store operates on a single channel in "Italy"
        And the store ships everywhere for free
        And the store has a product "PHP T-Shirt" priced at "$19.99"
        And I added product "PHP T-Shirt" to the cart
        And I am at the checkout addressing step

    @ui
    Scenario: Filling invoicing information as an italian company
        When I specify the email as "jon.snow@example.com"
        And I specify the billing address for the company "GoT SpA" - "Jon Snow" - "Viale Italia", "42100", "Reggio Emilia" - "Italy"
        And I specify a valid billing tax code and VAT number for an italian company
        And I specify a valid billing SDI code
        And I specify a valid billing PEC address
        And I complete the addressing step
        Then I should be on the checkout shipping step
