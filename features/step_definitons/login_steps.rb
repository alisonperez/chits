When /I am logged in as "(.*)" with password "(.*)"/ do |username, password|
  Then "I am on the home page"
  And "I fill in \"login\" with \"#{username}\""
  And "I fill in \"passwd\" with \"#{password}\""
  And "I press \"Login\""
  And "I should see \"logged in\""
end
