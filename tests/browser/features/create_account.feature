@chrome @firefox @vagrant
Feature: Create account

  Scenario Outline: Go to Create account page
    Given I go to Create account page at <path>
    Then form has Create account button

  Examples:
    | path                          |
    | Special:CreateAccount         |
    | Special:UserLogin/signup      |
    | Special:UserLogin?type=signup |

  Scenario: If no username is entered then an error is displayed
    Given I go to Create account page at Special:CreateAccount
    When I submit the form
    Then an error message is displayed

  Scenario: Create account via the API
    Given I have created account via the API
    When I log in as the new user
    Then I am logged in
