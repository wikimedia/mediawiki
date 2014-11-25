@chrome @clean @firefox @phantomjs
Feature: View History

  Scenario: View history link is present
    When I go to a random page 
    Then View history link should be present
      
  Scenario: Edit page and view history
    Given I go to the "History Test Page" page with content "This is a page that will have history"
    When I click Edit
      And I edit the page with "Edited and a random string"
      And I save the edit
      And the edited page content should contain "Edited and a random string"
      And I click View History
    Then I should see a link to a previous version of the page
