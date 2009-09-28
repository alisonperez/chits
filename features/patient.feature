Feature: Manage Patients
	In order to maximize the time for health care delivery
	As a chits user
	I want to be able to manage paients by searching,creating,updating and deleting patient information

	Scenario: Search the patient
    Given I am logged in as "admin" with password "admin"
    And I click "PATIENTS"
		When I fill in "first" with "Jose"
		And I fill in "last" with "Rizal"
		And I press "Search"
		Then I should see "No records found"

	Scenario: Add a new patient
		Given I am on the patient management form
		When I fill in the "patient_firstname" with "Andres"
		And I fill in the "patient_middlename" with "Cruz"
		And I fill in the "patient_lastname" with "Bonifacio"
		And I fill in the "patient_dob" with "02/03/1982"
		And I choose "Male" in the "patient_gender"
		And I fill in the "patient_mother" with "Maria"
		And I fill in the "patient_cellphone" with "09191234567"
		And I press "submitpatient"
		And I should see "Patient Andres Bonifacio was successfully been added"
		And I am on the old patient form
		And I fill "first" with "Andres"
		And I fill in "last" with "Bonifacio"
		Then I should see "Found 1 Record: Andres Bonifacio"
	Scenario: Update patient information
		Given I am on the old patient form
		When I fill in "first" with "Andres"
		And I fill in "last" with "Bonifacio"
		And I press "submitsearch"
		And I should see "Found 1 Record: Andres Bonifacio"
		And I click "Andres Bonifacio"
		And I should see patient information at the edit patient form
		And I fill in the "patient_middlename" with "Santos"
		And I fill in the "patient_dob" with "12/23/1990"
		And I press the "Update Patient"
		And I should be able to see "patient information is update"
		And I click "Andres Bonifacio"
		Then I should see "patient_middlename" is "Santos"
		And "patient_dob" is "12/23/1990";
	Scenario: Delete patient information
		Given I am on the old patient form
		When I fill in "first" with "Andres"
		And I fill in "last" with "Bonifacio"
		And I press "submitsearch"
		And I should see "Found 1 Record: Andres Bonifacio"
		And I click "Andres Bonifacio"
		And I should see patient information at the edit patient form
		And I press "delete patient"
		And I press "yes"
		Then I should not see "Andres Bonifacio"
