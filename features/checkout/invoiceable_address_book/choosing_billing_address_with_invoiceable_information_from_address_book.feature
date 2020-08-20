@invoiceable_address_book
Feature: Choosing an address from address book
    In order to quickly fill in my invoiceable address information during checkout
    As a Customer
    I want to be able to choose it from my address book

    Background:
        Given the store operates on a single channel in "Italy"
        And the store ships everywhere for free
        And the store has a product "PHP T-Shirt" priced at "$19.99"
        And I am a logged in customer
        And I have an address for the company "GoT SpA" - "Jon Snow" - "Viale Italia", "42100", "Reggio Emilia" - "Italy"
        And this address has also all the invoiceable information valid for an italian company

    @ui @javascript
    Scenario: Choosing billing address with invoicing information from address book
        Given I have product "PHP T-Shirt" in the cart
        And I am at the checkout addressing step
        When I choose "Viale Italia" street for billing address
        Then all invoicing information of the address I have in my address book should be filled in billing address
