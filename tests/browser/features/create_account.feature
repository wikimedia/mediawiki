@chrome @clean @firefox @phantomjs
Feature: Create account

  Scenario Outline: Go to Create account page
    Given I go to Create account page at <path>
    Then form has Create account button

  Examples:
    | path                          |
    | Special:CreateAccount         |
    | Special:UserLogin/signup      |
    | Special:UserLogin?type=signup |
