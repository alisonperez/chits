Feature: Readme file
  In order that other developers can contribute to the project
  As a CHITS developer
  I want to be able to read this readme file and be able to execute stories and write my own

  Scenario: Install cucumber
    Given I am running Ubuntu
    And I have followed the instructions and installed CHITS from github
    And I have not yet installed cucumber
    When I run "apt-get install ruby ruby-dev irb libxml2-dev libxslt1-dev"
    # We install rubygems by hand because the apt version uses strange paths
    And I run "wget http://rubyforge.org/frs/download.php/60718/rubygems-1.3.5.tgz"
    And I run "tar xvf rubygems-1.3.5.tgz"
    And I run "cd rubygems-1.3.5"
    And I run "tar xvf rubygems
    And I run "sudo ruby setup.rb"
    # Add github's gem source list
    And I run "gem sources -a http://gems.github.com"
    And I run "sudo apt-get install libxslt1-dev libxml2-dev"
    And I run "sudo gem install cucumber mechanize rspec webrat"
    And from the top level chits directory (var/www/chits)I run "cucumber"
    Then I should see cucumber successfully execute all of the stories in the features directory

  Scenario: Writing a new story feature
    Given I am in the features directory
    When I create a new feature file
    And I define any new steps in the step_definition directory
    And I run "cucumber"
    Then I should see my feature being executed
