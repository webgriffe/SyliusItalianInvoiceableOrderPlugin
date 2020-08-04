@filling_invoicing_information
Feature: Filling invoicing information as an italian individual
    In order to have my order properly invoiced
    As a Guest
    I want to be able to fill italian invoicing details for me as an individual

    Background:
        Given the store operates on a single channel in "Italy"
        And the store ships everywhere for free
        And the store has a product "PHP T-Shirt" priced at "$19.99"
        And I have product "PHP T-Shirt" in the cart
        And I am at the checkout addressing step

    @ui
    Scenario: Filling invoicing information as an italian individual
        When I specify the email as "jon.snow@example.com"
        And I specify the billing address for the individual "Jon Snow" - "Viale Italia", "42100", "Reggio Emilia" - "Italy"
        And I specify a valid billing tax code for an italian individual
        And I complete the addressing step
        Then I should be on the checkout shipping step
