@chrome @clean @firefox @internet_explorer_6 @internet_explorer_7 @internet_explorer_8 @internet_explorer_9 @internet_explorer_10 @login @phantomjs
Feature: Preferences

  Scenario: Preferences Appearance
    Given I am logged in
    When I navigate to Preferences
      And I click Appearance
    Then I can select skins
      And I can select image size
      And I can select thumbnail size
      And I can select Threshold for stub link
      And I can select underline preferences
      And I have advanced options checkboxes
      And I can click Save
      And I can restore default settings
      And I can select date format
      And I can see time offset section
      And I can see local time
      And I can select my time zone


  Scenario: Preferences Editing
    Given I am logged in
    When I navigate to Preferences
      And I click Editing
    Then I can select edit area font style
      And I can select section editing via edit links
      And I can select section editing by right clicking
      And I can select section editing by double clicking
      And I can select to prompt me when entering a blank edit summary
      And I can select to warn me when I leave an edit page with unsaved changes
      And I can select show edit toolbar
      And I can select show preview on first edit
      And I can select show preview before edit box
      And I can select live preview


  Scenario: Preferences User profile
    Given I am logged in
    When I navigate to Preferences
      And I click User profile
    Then I can see my Basic informations
      And I can change my language
      And I can change my gender
      And I can see my signature
      And I can change my signature
      And I can see my email
      And I can click Save
      And I can restore default settings
