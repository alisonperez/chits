Feature: Manage users
  In order to handle healthcare workers coming and going
  As a chits administrator
  I want to be able to create, update and delete users

  Scenario: Visit admin page
    Given I am logged in as "admin" with password "admin"
    When I click "ADMIN"
    Then I should see "SITE USER FORM"

# @reset_users uses cucumber before and after tags to specify that this scenario should save the game_user table before executing the scenario, and then reload the original after the scenario executes. The code for @reset_users is defined in support/hooks.rb
  @reset_users
  Scenario: Create a user
    Given I am logged in as "admin" with password "admin"
    And I am on the admin page
    And I should not see "Jackson, Michael"
    When I fill in "user_firstname" with "Michael"
    And I fill in "user_middle" with "King of Pop"
    And I fill in "user_lastname" with "Jackson"
    And I fill in "user_dob" with "08/08/1968"
    And I choose "user_gender"
    And I fill in "user_pin" with "1234"
    And I select "Nurse" from "role_id"
    And I select "english" from "lang_id"
    And I fill in "user_login" with "jacko"
    And I fill in "user_password" with "neverland"
    And I fill in "confirm_password" with "neverland"
    And I check "isactive"
    And I press "Add User"
    And I should see "Jackson, Michael"
    And I press "Sign Out"
    And I am on the home page
    And I fill in "login" with "jacko"
    And I fill in "passwd" with "neverland"
    And I press "Login"
    Then I should see "logged in"

  @reset_users
  Scenario: Update a user
    Given I am logged in as "admin" with password "admin"
    And I am on the admin page
    When I click "Perez, Alison"
    And I should not see "alison@kung.fu"
    And I fill in "user_email" with "alison@kung.fu"
    And I press "Update User"
    And I click "Perez, Alison"
    Then I should not see "alison@cm.upm.edu.ph"
    And I should see "alison@kung.fu"
    
  @reset_users
  Scenario: Delete a user
    Given I am logged in as "admin" with password "admin"
    And I am on the admin page
    When I click "Perez, Alison"
    And I press "Delete User"
    And I press "Yes"
    Then I should not see "Perez, Alison"
