Feature: Manage Patients
	In order to maximize the time for health care delivery
	As a chits user
	I want to be able to manage patients by searching,creating,updating and deleting patient information

	Scenario: Search the patient
    Given I am logged in as "user" with password "user"
    And I click "RECORDS"
		When I fill in "first" with "Jose"
		And I fill in "last" with "Rizal"
		And I press "Search"
		Then I should see "Jose Rizal"

	@reset_users
	Scenario: Add a new patient
		Given I am logged in as "user" with password "user"
		And I click "RECORDS"
		And I am on the patient management form
		When I fill in "patient_firstname" with "Andres"
		And I fill in "patient_middle" with "Cruz"
		And I fill in "patient_lastname" with "Bonifacio"
		And I fill in "patient_dob" with "02/03/1982"
		And I select "Male" from "patient_gender"
		And I fill in "patient_mother" with "Maria"
		And I fill in "patient_cellphone" with "09191234567"
		And I press "Add Patient"
		And I should see "Patient Andres Bonifacio was successfully been added"
		And I am on the old patient form
		And I fill in "first" with "Andres"
		And I fill in "last" with "Bonifacio"
		Then I should see "Found 1 Record: Andres Bonifacio"

	@reset_users
	Scenario: Update patient information
		Given I am logged in as "user" with password "user"
		And I click "RECORDS"
		And I am on the patient management form
		When I fill in "first" with "Jose"
		And I fill in "last" with "Rizal"
		And I press "Search"
		Then I should see "Found"
		And I click "Rizal"
		And I should see patient information at the edit patient form
		And I fill in "patient_middlename" with "Santos"
		And I fill in "patient_dob" with "12/23/1990"
		And I press "Update Patient"
		And I should be able to see "patient information is update"
		And I click "Andres Bonifacio"
		Then I should see "patient_middlename" is "Santos"
		And I should see "patient_dob" is "12/23/1990";

	@reset_users
	Scenario: Delete patient information
		Given I am logged in as "admin" with password "admin"
		And I click "CONSULTS"
		And I am on the patient management form
		When I fill in "first" with "Jose"
		And I fill in "last" with "Rizal"
		And I press "Search"
		Then I should see "Jose Rizal"
		And I click "Rizal"		
		And I press "Delete Patient"
		And I press "Yes"
		Then I should not see "Andres Bonifacio"