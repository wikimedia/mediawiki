
Feature: Create Page With Wiki Link

  Scenario: Create Page With Wiki Link
    Given I go to the "Created Test Page" page with content "This is a [[Main Page|link to the Main Page]] right here."
    When I click the Main Page link
    Then I should be on the Main Page