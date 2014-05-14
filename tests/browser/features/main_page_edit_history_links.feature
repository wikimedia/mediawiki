Feature: Main Page Edit View History Links

  Scenario: Main Page Edit View History links exist
    Given I open the main wiki URL
    When I see the Main Page
    Then I should see a link for View History
      And I should see a link for Edit