# RSpec
require 'spec/expectations'

# Webrat
require 'webrat'

require 'test/unit/assertions'
World(Test::Unit::Assertions)

Webrat.configure do |config|
  config.mode = :mechanize
end

World do
  session = Webrat::Session.new
  session.extend(Webrat::Methods)
  session.extend(Webrat::Matchers)
  session
end

# Helper method for running shell commands
def run(command, verbose = false, message = nil)
  if verbose then
    puts "#{message}"
    puts command
    result = `#{command}`
    puts result
    return result
  else
    `#{command}`
  end
end



# Switch to the test database
@database_config_path = File.dirname(__FILE__)+"/../../modules/_dbselect.php"
@original_database_config = File.read(@database_config_path)
@@test_database_location = "localhost"
@@test_database_name = "chits_testing"
@@test_database_username = "chits_tester"
@@test_database_password = "useless_password"
@path_to_core_data = File.dirname(__FILE__)+"/../../db/core_data.sql"

File.open(@database_config_path, "w") do |file|
  file.puts "
<?php
session_start();
$database_location = '#{@@test_database_location}';
$database_name = '#{@@test_database_name}';
$database_username = '#{@@test_database_username}';
$database_password = '#{@@test_database_password}';
$_SESSION['dbname'] = $database_name;
$_SESSION['dbuser'] = $database_username;
$_SESSION['dbpass'] = $database_password;
$conn = mysql_connect($database_location, $database_username, $database_password);
$db->connectdb($database_name);
?>
"
end

# Check to see if test database exists, if not create the user p
puts "Checking that test database exists, then switching to it..."
unless(run("echo \"SHOW DATABASES;\" | mysql -u #{@@test_database_username} --password=#{@@test_database_password} 2>&1").match @@test_database_name ) then
  puts "\nOops! Looks like you don't have a test database yet, so you need to create one. Luckily this is easy!\nRun the following commands and enter your password when necessary (your root mysql password may be blank).\n\n"
  puts "echo \"CREATE DATABASE #{@@test_database_name};\" | mysql -u root -p;"
#  puts "echo \"INSERT INTO user SET user='#{@@test_database_username}',password=password('#{@@test_database_password}'),host='#{@@test_database_location}';\" | mysql -u root -p mysql;"

  puts "echo \"GRANT ALL PRIVILEGES ON #{@@test_database_name}.* TO #{@@test_database_username}@#{@@test_database_location} IDENTIFIED BY '#{@@test_database_password}'\" | mysql -u root -p"
  puts "mysql -u #{@@test_database_username} --password=#{@@test_database_password} #{@@test_database_name} < #{@path_to_core_data};"
  exit
end

# Switch back to whatever was loaded originally
def rollback_to_core_data
  File.open(@database_config_path, "w") do |file|
    file.puts @original_database_config
  end
#  puts "Cucumber finished, Resetting test database"
#  run "mysql -u #{@@test_database_username} --password=#{@@test_database_password} #{@@test_database_name} < #{@path_to_core_data}"
end

at_exit do
  rollback_to_core_data
end

module Webrat
  # For some reason we are getting duplicated GET params on the current_url, so monkeypatch to fix it
  class Link
    def absolute_href
      if href =~ /^\?/
        "#{@session.current_url.gsub(/\?.*/,"")}#{href}"
      elsif href !~ %r{^https?://} && (href !~ /^\//)
        "#{@session.current_url}/#{href}"
      else
        href
      end
    end
  end

  # For extra debug info we monkeypatch this in
  module Locators  
    class Locator # :nodoc:
      def locate!
        locate || raise(NotFoundError.new(error_message))        
      rescue Webrat::NotFoundError => e
        filepath = '/tmp/webrat_debug.html'
        File.open(filepath, "w") do |file|
          file.puts @session.send(:response_body)
        end
        raise "#{e.message}\n#{@session.send(:response_body).gsub(/\n/, "\n  ")}\nURL: #{@session.current_url}\nResponse saved: #{filepath}"  
      end
    end
  end
end

