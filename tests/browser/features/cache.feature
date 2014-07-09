@en.wikipedia.beta.wmflabs.org
Feature: Cache

  Scenario: Editing an article should purge the desktop cache
    Given I edit a random page using desktop web interface
    When I visit the page using desktop web interface as an anonymous user
    Then the page should be up to date

  Scenario: Editing an article should purge the mobile cache
    Given I edit a random page using desktop web interface
    When I visit the page using mobile web interface as an anonymous user
    Then the page should be up to date

  Scenario: Uploading a new version of a file should clear the cached versions of the thumbnails
    Given I have upload a file on Commons
      And generated a thumbnail
    When I upload a new version of the file
    Then the thumbnail should be a different file
