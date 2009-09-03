Feature: Manage users
  In order to handle healthcare workers coming and going
  As a chits administrator
  I want to be able to create, update and delete users

  Scenario: Visit admin page
    Given I am logged in as "admin" with password "admin"
    When I click "ADMIN"
    Then I should see "SITE USER FORM"

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

  Scenario: Update a user

  Scenario: Delete a user

