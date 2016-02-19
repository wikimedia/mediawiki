@chrome @firefox @vagrant
Feature: Main Page View History Links

  Background:
  Given I open the main wiki URL

  Scenario: Main Page View History links exist
    Then I should see a link for View History

  Scenario: Main Page Sidebar Links
    Then I should see a link for Recent changes
      And I should see a link for Random page
      And I should see a link for Help
      And I should see a link for What links here
      And I should see a link for Related changes
      And I should see a link for Special pages
      And I should see a link for Printable version
      And I should see a link for Permanent link
      And I should see a link for Page information
