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
       
  Scenario: Search A Family Folder
    Given I am logged in as "user" with password "user"
    And I click "FAMILY FOLDERS"
    When I fill in "family_number" with "1"
    And I press "Search"
    Then I should see "COUNT"

   Scenario: Update Family Folder Details
    Given I am logged in as "user" with password "user"
    And I click "FAMILY FOLDERS"
    When I fill in "family_number" with "37"
    And I press "Search"
    And I should see "SELECTED FAMILY"
    And I click "edit"
    And I fill in "family_address" with "6789 XYZ Street"
    And I select "Brgy 2" from "barangay"
    And I press "Update Folder"
    Then I should see "6789 XYZ Street"
    And I should see "Brgy 2"


   Scenario: Delete Family Folder
    Given I am logged in as "admin" with password "admin"
    And I click "PATIENTS"
    And I click "Family Folders"
    When I click "No members"
    And I should see "1"
    And I click "delete"
    And I press "Yes"
    Then I should not see "1"