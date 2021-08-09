@filling_invoicing_information
Feature: Italian invoicing information validation
    In order to have my order properly invoiced as an italian company or individual
    As a Guest
    I want to be informed if I enter invalid invoicing information

    Background:
        Given the store operates on a single channel in "Italy"
        And the store ships everywhere for free
        And the store has a product "PHP T-Shirt" priced at "$19.99"
        And I have product "PHP T-Shirt" in the cart
        And I am at the checkout addressing step

    @ui
    Scenario: Validating the tax code for an italian individual
        When I specify the email as "jon.snow@example.com"
        And I specify the billing address for the individual "Jon Snow" - "Viale Italia", "42100", "Reggio Emilia" - "Italy"
        And I specify an invalid tax code for an italian individual
        And I complete the addressing step
        Then I should be notified that the billing tax code is not valid

    @ui
    Scenario: Validating the tax code for an italian company
        When I specify the email as "jon.snow@example.com"
        And I specify the billing address for the company "GoT SpA" - "Jon Snow" - "Viale Italia", "42100", "Reggio Emilia" - "Italy"
        And I specify an invalid tax code for an italian company
        And I complete the addressing step
        Then I should be notified that the billing tax code is not valid

    @ui
    Scenario: Validating the VAT number for an italian company
        When I specify the email as "jon.snow@example.com"
        And I specify the billing address for the company "GoT SpA" - "Jon Snow" - "Viale Italia", "42100", "Reggio Emilia" - "Italy"
        And I specify an invalid VAT number for an italian company
        And I complete the addressing step
        Then I should be notified that the billing VAT number is not valid

    @ui
    Scenario: Validating the SDI code
        When I specify the email as "jon.snow@example.com"
        And I specify the billing address for the company "GoT SpA" - "Jon Snow" - "Viale Italia", "42100", "Reggio Emilia" - "Italy"
        And I specify an invalid SDI code
        And I complete the addressing step
        Then I should be notified that the billing SDI code is not valid

    @ui
    Scenario: Requiring all invoicing information for an italian individual
        When I specify the email as "jon.snow@example.com"
        And I specify the billing address for the individual "Jon Snow" - "Viale Italia", "42100", "Reggio Emilia" - "Italy"
        And I complete the addressing step
        Then I should be notified that the billing tax code is required

    @ui
    Scenario: Requiring all invoicing information for an italian company
        When I specify the email as "jon.snow@example.com"
        And I specify the billing address for the company "GoT SpA" - "Jon Snow" - "Viale Italia", "42100", "Reggio Emilia" - "Italy"
        And I complete the addressing step
        Then I should be notified that the billing tax code is required
        And I should be notified that the billing VAT number is required
        And I should be notified that one between the billing SDI code and PEC address is required

    @ui
    Scenario: Not requiring PEC address for an italian company if SDI Code is present and valid
        When I specify the email as "jon.snow@example.com"
        And I specify the billing address for the company "GoT SpA" - "Jon Snow" - "Viale Italia", "42100", "Reggio Emilia" - "Italy"
        And I specify a valid billing SDI code
        And I complete the addressing step
        Then I should be notified that the billing tax code is required
        And I should be notified that the billing VAT number is required
        And I should not be notified that one between the billing SDI code and PEC address is required

    @ui
    Scenario: Not requiring SDI code for an italian company if PEC address is present and valid
        When I specify the email as "jon.snow@example.com"
        And I specify the billing address for the company "GoT SpA" - "Jon Snow" - "Viale Italia", "42100", "Reggio Emilia" - "Italy"
        And I specify a valid billing PEC address
        And I complete the addressing step
        Then I should be notified that the billing tax code is required
        And I should be notified that the billing VAT number is required
        And I should not be notified that one between the billing SDI code and PEC address is required
