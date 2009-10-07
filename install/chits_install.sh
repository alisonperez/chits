#!/bin/bash
if [ -z "$SUDO_USER" ]; then
    echo "$0 must be called from sudo"
    exit 1
fi
apt-get --assume-yes install apache2 mysql-server php5 php5-mysql openssh-server git-core wget ruby libxml2-dev libxslt1-dev ruby1.8-dev rdoc1.8 irb1.8 libopenssl-ruby1.8 build-essential

chmod 777 /var/www
wget -O /etc/php5/apache2/php.ini http://github.com/mikeymckay/chits/raw/master/install/php.ini.sample
/etc/init.d/apache2 restart
#no sudo
su $SUDO_USER -c "git clone git://github.com/mikeymckay/chits.git /var/www/chits"
su $SUDO_USER -c "cp /var/www/chits/modules/_dbselect.php.sample /var/www/chits/modules/_dbselect.php"
echo "You will now be prompted for your mysql root user password a few times. This is the one you entered in the blue box when you installed mysql."
echo "CREATE DATABASE example_database;" | mysql -u root -p
mysql -u root -p example_database < /var/www/chits/db/core_data.sql
echo "INSERT INTO user SET user='example_user',password=password('example_password'),host='localhost';FLUSH PRIVILEGES;GRANT ALL PRIVILEGES ON example_database.* to example_user@localhost IDENTIFIED BY 'example_password';" | mysql -u root mysql -p

#Setup cucumber
wget http://rubyforge.org/frs/download.php/60718/rubygems-1.3.5.tgz
tar xvf rubygems-1.3.5.tgz --directory /tmp
ruby /tmp/rubygems-1.3.5/setup.rb
ln -s /usr/bin/gem1.8 /usr/bin/gem
gem sources -a http://gems.github.com
echo "Installing testing tools"
gem install cucumber mechanize rspec webrat --no-ri

echo "Setting up test database"
echo "You will again be prompted for your mysql root user password a few times. This is the one you entered in the blue box when you installed mysql."
echo "CREATE DATABASE chits_testing;" | mysql -u root -p;
echo "GRANT ALL PRIVILEGES ON chits_testing.* TO chits_tester@localhost IDENTIFIED BY 'useless_password'" | mysql -u root -p
mysql -u chits_tester --password=useless_password chits_testing < /var/www/chits/features/support/../../db/core_data.sql

cucumber /var/www/chits/features
