@chrome @clean @en.wikipedia.beta.wmflabs.org @firefox @phantomjs @test2.wikipedia.org
Feature: Create account

  Scenario Outline: Go to Create account page
    Given I go to Create account page at <path>
    Then form has Create account button

  Examples:
    | path                          |
    | Special:CreateAccount         |
    | Special:UserLogin/signup      |
    | Special:UserLogin?type=signup |
