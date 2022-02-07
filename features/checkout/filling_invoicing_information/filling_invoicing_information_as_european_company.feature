@filling_invoicing_information
Feature: Filling invoicing information as an european company
    In order to have my order properly invoiced
    As a Guest
    I want to be able to fill invoicing details for my european company

    Background:
        Given the store operates on a single channel in "Italy"
        And channel "Italy" billing data is "Merchant S.p.A.", "Via Roma 12", "12345" "Milano", "Italy" with "06549840962" tax ID
        And the store operates in "Germany"
        And the store operates in "Greece"
        And this channel operates in the "Germany" country
        And this channel operates in the "Greece" country
        And the store ships everywhere for free
        And the store has a product "PHP T-Shirt" priced at "$19.99"
        And I have product "PHP T-Shirt" in the cart
        And I am at the checkout addressing step

    @ui
    Scenario: Filling invoicing information as an european company
        When I specify the email as "jon.snow@example.com"
        And I specify the billing address for the company "GoT GmbH" - "Jon Snow" - "Genslerstraße 9", "10829", "Berlin" - "Germany"
        And I specify a valid billing VAT number for a german company
        And I complete the addressing step
        Then I should be on the checkout shipping step

    @ui
    Scenario: Filling invoicing information as a greek company
        When I specify the email as "jon.snow@example.com"
        And I specify the billing address for the company "GoT GmbH" - "Jon Snow" - "EO Kalampakas Grevenon", "422 00", "Kalampaka" - "Greece"
        And I specify a valid billing VAT number for a greek company
        And I complete the addressing step
        Then I should be on the checkout shipping step
