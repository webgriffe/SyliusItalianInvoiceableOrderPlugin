@filling_invoicing_information
Feature: Preventing complete checkout without billing recipient type
    In order to have my order properly invoiced
    As a Guest
    I want to be informed if I complete the checkout without billing recipient type in the billing address

    Background:
        Given the store operates on a single channel in "United States"
        And the store ships everywhere for free
        And the store allows paying with "Bank Transfer"
        And the store has a product "PHP T-Shirt" priced at "$19.99"
        And I have product "PHP T-Shirt" in the cart
        And I am at the checkout addressing step

    @ui
    Scenario:
        Given I specify the email as "jon.snow@example.com"
        And I specify the billing address as "Los Angeles", "Sunset Boulevard", "90210", "United States" for "Jon Snow"
        And I do not specify the billing recipient type in the billing address
        And I complete the addressing step
        And I proceed with "Free" shipping method
        And I choose "Bank Transfer" payment method
        When I confirm my order
        Then I should be notified that the billing recipient type is required for the order
