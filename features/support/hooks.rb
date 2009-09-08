Before ('@reset_users') do
  # Save user table data
  save_table("game_user")
end

After ('@reset_users') do
  # Reload user table data
  load_table("game_user")
end

def save_table(table_name)
  run "mysqldump -u #{@@test_database_username} --password=#{@@test_database_password} #{@@test_database_name} #{table_name}> /tmp/#{table_name}"

end

def load_table(table_name)
  run "mysql -u #{@@test_database_username} --password=#{@@test_database_password} #{@@test_database_name} < /tmp/#{table_name}"
end
