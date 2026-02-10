@applying_taxes
Feature: Apply correct taxes based on order address
    In order to pay proper amount when buying goods
    As a Customer or Visitor
    I want to have correct taxes applied to my order

    Background:
        Given the store operates on a single channel worldwide
        And the store uses the "Italian tax calculation strategy" tax calculation strategy
        And there is a tax zone "EU" containing all European Union countries
        And there is a tax zone "Extra EU" containing all countries outside the European Union
        And there is a shipping zone "Global" containing all countries of the world
        And the store ships everywhere for Free
        And the store allows paying Offline
        And the store has "IVA 22%" tax rate of 22% for "IVA Ordinaria" within the "EU" zone
        And the store has a product "PHP T-Shirt" priced at "$100.00"
        And it belongs to "IVA Ordinaria" tax category

    @ui
    Scenario: Paying taxes while ordering as an italian individual
        Given I added product "PHP T-Shirt" to the cart
        And I am at the checkout addressing step
        And I specify the email as "italian-individual@email.com"
        And I specify a valid italian individual billing address
        And I complete the addressing step
        And I proceed with "Free" shipping method and "Offline" payment
        Then I should be on the checkout complete step
        And my order total should be "$122.00"
        And my tax total should be "$22.00"

    @ui
    Scenario: Paying taxes while ordering as an italian company
        Given I added product "PHP T-Shirt" to the cart
        And I am at the checkout addressing step
        And I specify the email as "italian-company@email.com"
        And I specify a valid italian company billing address
        And I complete the addressing step
        And I proceed with "Free" shipping method and "Offline" payment
        Then I should be on the checkout complete step
        And my order total should be "$122.00"
        And my tax total should be "$22.00"

    @ui
    Scenario: Paying taxes while ordering as an EU individual
        Given I added product "PHP T-Shirt" to the cart
        And I am at the checkout addressing step
        And I specify the email as "german-individual@email.com"
        And I specify a valid german individual billing address
        And I complete the addressing step
        And I proceed with "Free" shipping method and "Offline" payment
        Then I should be on the checkout complete step
        And my order total should be "$122.00"
        And my tax total should be "$22.00"

    @ui
    Scenario: Not paying taxes while ordering as an EU company
        Given I added product "PHP T-Shirt" to the cart
        And I am at the checkout addressing step
        And I specify the email as "german-company@email.com"
        And I specify a valid german company billing address
        And I complete the addressing step
        And I proceed with "Free" shipping method and "Offline" payment
        Then I should be on the checkout complete step
        And my order total should be "$100.00"
        And my tax total should be "$0.00"

    @ui
    Scenario: Not paying taxes while ordering as an extra EU individual
        Given I added product "PHP T-Shirt" to the cart
        And I am at the checkout addressing step
        And I specify the email as "usa-individual@email.com"
        And I specify a valid US individual billing address
        And I complete the addressing step
        And I proceed with "Free" shipping method and "Offline" payment
        Then I should be on the checkout complete step
        And my order total should be "$100.00"
        And my tax total should be "$0.00"

    @ui
    Scenario: Not paying taxes while ordering as an extra EU company
        Given I added product "PHP T-Shirt" to the cart
        And I am at the checkout addressing step
        And I specify the email as "usa-company@email.com"
        And I specify a valid US company billing address
        And I complete the addressing step
        And I proceed with "Free" shipping method and "Offline" payment
        Then I should be on the checkout complete step
        And my order total should be "$100.00"
        And my tax total should be "$0.00"
