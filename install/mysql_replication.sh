#!/bin/sh
if [ -z "$SUDO_USER" ]; then
    echo "$0 must be called from sudo"
    exit 1
fi

# TODO check that we are server IP or hostname or something

echo "Enter mysql root user password
"
read MYSQL_ROOT_PASSWORD

echo "Enter chits_live mysql password
"
read MYSQL_CHITS_LIVE_PASSWORD

echo "Enter IP addresses for slaves:
"
read SLAVE_IPS

echo "Setting up ${SLAVE_IPS} as mysql slaves"

echo "Configuring self as master"

# setup mysql configuration

# Comment out the bind address so mysql accepts non-local connections
sed -i 's/^bind-address.*127.0.0.1/#&/' /etc/mysql/my.cnf

# Prepend the following to /etc/mysql/my.conf
  echo "
# ------------------------------
# Added by tarlac_install script
# ------------------------------
log-bin = /var/log/mysql/mysql-bin.log
binlog-do-db=sample_database
server-id=1
# ------------------------------

"|cat - /etc/mysql/my.conf > /tmp/out && mv /tmp/out /etc/mysql/my.conf

/etc/init.d/mysql restart

echo "GRANT REPLICATION SLAVE ON *.* TO 'chits_live'@'%' IDENTIFIED BY '${MYSQL_CHITS_LIVE_PASSWORD}'; FLUSH PRIVILEGES; USE chits_live" | mysql -u root -p$MYSQL_ROOT_PASSWORD

# TODO http://www.ghacks.net/2009/04/09/set-up-mysql-database-replication/
