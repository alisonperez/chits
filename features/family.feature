Feature: Family Folders

   In order to efficiently manage patients into families and readily access family data
   As a chits user
   I want to be able to create, search, update and delete family folders  
    
   Scenario: Add A New Family Folder
    Given I am logged in as "user" with password "user"
    And I click "FAMILY FOLDERS"
    When I fill in "family_address" with "1234 ABC Street"
    And I select "Brgy 1" from "barangay"
    And I press "Create Folder"
    Then I should see "No members"
       
#  Scenario: Search A Family Folder

#   Scenario: Update Family Folder Details

#   Scenario: Delete Family Folder

