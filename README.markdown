# Install Method 1

This is the preferred method. It sets up a web server, the database and other required software. It uses git to download the latest version of chits and it also includes a full testing suite which is executed at the end of the installation to ensure that everything worked as expected.

Download and run the chits_install.sh script as sudo:

    wget http://github.com/mikeymckay/chits/raw/master/install/chits_install.sh
    sudo ./chits_install.sh

# Install Method 2

# How to install chits

Assuming a fresh install of ubuntu, these are the steps required to get chits up and running. Extra steps should be taken to make this stuff secure. A script to automate this will be coming soon. All of the lines that look like code are meant to be executed as is on the command line. Just copy and paste the entire line to your terminal window.

#A. Setting up necessary softwares (Apache, MySQL ,PHP)

Install web server, programming language, secure shell server and a code management tool. You will be prompted for your root password. Later you will be asked to create a root password for mysql in a blue screen - remember what you choose you will need it below (it can be empty if you are not going to put real data into your database))

    sudo apt-get install apache2 mysql-server php5 php5-mysql openssh-server git-core wget

Download the latest and greatest and most stable chits

    sudo chmod 777 /var/www
    git clone git://github.com/mikeymckay/chits.git /var/www/chits

#B. Configuring php.ini, httpd.conf, mysql

Download and overwrite your existing php.ini then restart the web server

    sudo wget -O /etc/php5/apache2/php.ini http://github.com/mikeymckay/chits/raw/master/install/php.ini.sample
    sudo /etc/init.d/apache2 restart

Setup the database - you will need the mysql password you setup earlier. Create, populate and setup the users:

    echo "CREATE DATABASE example_database;" | mysql -u root -p
    mysql -u root -p example_database < /var/www/chits/db/core_data.sql
    echo "INSERT INTO user SET user='example_user',password=password('example_password'),host='localhost';FLUSH PRIVILEGES;GRANT ALL PRIVILEGES ON example_database.* to example_user@localhost IDENTIFIED BY 'example_password';" | mysql -u root mysql -p

#C. Configuring chits config file

    cp /var/www/chits/modules/_dbselect.php.sample /var/www/chits/modules/_dbselect.php

#D. Test it

Access chits in your browser (Login using 'admin' and password 'admin'):

    firefox http://localhost/chits/

#E. Testing

Install cucumber according to the instructions found here:
http://github.com/mikeymckay/chits/blob/master/features/README.markdown

# Install Method 3

(this method is untested, please send feedback)

Run chits inside VirtualBox (runs on Windows/Linux/Mac). This image also has cucumber installed and setup.

Download a 145MB compressed Ubuntu image with chits preinstalled:
  http://www.vdomck.org/chits_appliance.7z

(You will need 7zip to uncompress it)

You will also need the virtualbox configuration file:
  http://www.vdomck.org/chits_appliance.xml
