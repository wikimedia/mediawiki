@chrome @clean @en.wikipedia.beta.wmflabs.org @firefox @phantomjs @test2.wikipedia.org
Feature: Edit Page

  Scenario: Create and edit page
    Given I go to the "Editing Test Page" page with content "This is a page to test editing"
    When I click Edit
      And I edit the page with "Edited and a random string"
      And I save the edit
    Then the edited page content should contain "Edited and a random string"
