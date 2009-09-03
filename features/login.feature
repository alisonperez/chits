Feature: Login
  In order to manage patients
  As a healthcare worker
  I want access to CHITS features to require a login

  Scenario: View the login page
    Given I am on the home page
    Then I should see "chits"

  Scenario: Login with default admin password
    Given I am on the home page
    When I fill in "login" with "admin"
    And I fill in "passwd" with "admin"
    And I press "Login"
    Then I should see "logged in"

  Scenario: Login with default admin password
    Given I am logged in as "admin" with password "admin"
    Then I should see "logged in"

  Scenario: Logout
    Given I am logged in as "admin" with password "admin"
    When I press "Sign Out"
    Then I should see "SIGN IN"
