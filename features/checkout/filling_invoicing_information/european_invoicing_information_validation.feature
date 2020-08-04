@filling_invoicing_information
Feature: Italian invoicing information validation
    In order to have my order properly invoiced an european company or individual
    As a Guest
    I want to be informed if I enter invalid invoicing information

    Background:
        Given the store operates on a single channel in "Italy"
        And channel "Italy" uses "minimit/xtend-theme-sylius" theme
        And channel "Italy" billing data is "Merchant S.p.A.", "Via Roma 12", "12345" "Milano", "Italy" with "06549840962" tax ID
        And the store operates in "Germany"
        And this channel operates in the "Germany" country
        And the store ships everywhere for free
        And the store has a product "PHP T-Shirt" priced at "$19.99"
        And I have product "PHP T-Shirt" in the cart
        And I am at the checkout addressing step

    @ui
    Scenario: Validating european VAT number
        When I specify the email as "jon.snow@example.com"
        And I specify the billing address for the company "GoT GmbH" - "Jon Snow" - "Genslerstraße 9", "10829", "Berlin" - "Germany"
        And I specify an invalid VAT number for a german company
        And I complete the addressing step
        Then I should be notified that the billing VAT number is not valid

    @ui
    Scenario: Requiring VAT number to european company
        When I specify the email as "jon.snow@example.com"
        And I specify the billing address for the company "GoT GmbH" - "Jon Snow" - "Genslerstraße 9", "10829", "Berlin" - "Germany"
        And I complete the addressing step
        Then I should be notified that the billing VAT number is required
