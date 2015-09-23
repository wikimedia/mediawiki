@chrome @clean @firefox @internet_explorer_6 @internet_explorer_7 @internet_explorer_8 @internet_explorer_9 @internet_explorer_10 @phantomjs
Feature: File

 Scenario: Anonymous goes to file that does not exist
   Given I am at file that does not exist
   Then page should show that no such file exists

 @login
 Scenario: Logged-in user goes to file that does not exist
   Given I am logged in
     And I am at file that does not exist
   Then page should show that no such file exists
