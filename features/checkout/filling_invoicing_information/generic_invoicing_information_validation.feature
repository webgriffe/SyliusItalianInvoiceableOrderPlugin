@filling_invoicing_information
Feature: Italian invoicing information validation
    In order to have my order properly invoiced
    As a Guest
    I want to be informed if I enter invalid invoicing information

    Background:
        Given the store operates on a single channel in "United States"
        And the store ships everywhere for free
        And the store has a product "PHP T-Shirt" priced at "$19.99"
        And I have product "PHP T-Shirt" in the cart
        And I am at the checkout addressing step

    @ui
    Scenario: Validating the PEC address
        When I specify the email as "jon.snow@example.com"
        And I specify the billing address for the company "GoT Inc." - "Jon Snow" - "Sunset Boulevard", "90210", "Los Angeles" - "United States"
        And I specify an invalid PEC address
        And I complete the addressing step
        Then I should be notified that the billing PEC address is not valid

    @ui
    Scenario: Requiring company name for companies
        When I specify the email as "jon.snow@example.com"
        And I specify the billing address for the company "" - "Jon Snow" - "Sunset Boulevard", "90210", "Los Angeles" - "United States"
        And I complete the addressing step
        Then I should be notified that the billing company name is required

    @ui
    Scenario: Not requiring SDI code nor PEC address
        When I specify the email as "jon.snow@example.com"
        And I specify the billing address for the company "" - "Jon Snow" - "Sunset Boulevard", "90210", "Los Angeles" - "United States"
        And I complete the addressing step
        Then I should be notified that the billing company name is required
        And I should not be notified that one of the billing SDI code or PEC address is required
