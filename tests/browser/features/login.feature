@chrome @firefox @vagrant
Feature: Log in

  Background:
    Given I am at Log in page

  Scenario: Go to Log in page
    Then Username element should be there
      And Password element should be there
      And Log in element should be there

  Scenario: Log in without entering credentials
    When I log in without entering credentials
    Then error box should be visible

  Scenario: Log in without entering password
    When I log in without entering password
    Then error box should be visible

  Scenario: Log in with incorrect username
    When I log in with incorrect username
    Then error box should be visible

  Scenario: Log in with incorrect password
    When I log in with incorrect password
    Then error box should be visible

  Scenario: Log in with valid credentials
    When I log in
    Then error box should not be visible
