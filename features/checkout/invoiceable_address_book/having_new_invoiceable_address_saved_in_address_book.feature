@invoiceable_address_book
Feature: Having new invoiceable addresses saved in the address book after checkout
  In order to ease my address management
  As a Customer
  I want new invoiceable addresses provided during checkout to be saved in my address book

  Background:
    Given the store operates on a single channel in "Italy"
    And the store has a product "Lannister Coat" priced at "$19.99"
    And the store ships everywhere for free
    And the store allows paying with "Cash on Delivery"
    And I am a logged in customer
    And I have product "Lannister Coat" in the cart

  @ui
  Scenario: Invoiceable address already existent in my book don't get saved again
    Given I have an address for the company "GoT SpA" - "Jon Snow" - "Viale Italia", "42100", "Reggio Emilia" - "Italy"
    And this address has also all the invoiceable information valid for an italian company
    And I am at the checkout addressing step
    When I specify the billing address for the company "GoT SpA" - "Jon Snow" - "Viale Italia", "42100", "Reggio Emilia" - "Italy"
    And I specify the same invoiceable information of the address I have in my address book
    And I complete the addressing step
    And I proceed with "Free" shipping method and "Cash on Delivery" payment
    And I confirm my order
    Then I should have a single address in my address book

  @ui
  Scenario: Different invoiceable address information provided during checkout are saved in my book as new address
    Given I have an address for the company "GoT SpA" - "Jon Snow" - "Viale Italia", "42100", "Reggio Emilia" - "Italy"
    And this address has also all the invoiceable information valid for an italian company
    And I am at the checkout addressing step
    When I specify the billing address for the company "GoT SpA" - "Jon Snow" - "Viale Italia", "42100", "Reggio Emilia" - "Italy"
    And I specify the same invoiceable information of the address I have in my address book
    But I specify a different SDI code from that of the address I have in the address book
    And I complete the addressing step
    And I proceed with "Free" shipping method and "Cash on Delivery" payment
    And I confirm my order
    Then I should have 2 addresses in my address book
