#!/bin/sh
if [ -z "$SUDO_USER" ]; then
    echo "$0 must be called from sudo"
    exit 1
fi

echo "Do you want to upgrade all packages? ([y]/n)"
read UPGRADE_ALL

echo "Do you want to remove un-needed packages like games, music players and email? ([y]/n)"
read REMOVE

# These are for all configurations
PROGRAMS_TO_INSTALL='openssh-server wget'

if [ ! "${REMOVE}" = "n" ]; then
  PROGRAMS_TO_REMOVE="gnome-games gnome-games-data openoffice.org-common f-spot ekiga evolution pidgin totem totem-common brasero rhythmbox synaptic gimp"
fi

# Call "install wget" to add wget to the list of programs to install
install () {
  PROGRAMS_TO_INSTALL="${PROGRAMS_TO_INSTALL} ${1}"
}

remove () {
  PROGRAMS_TO_REMOVE="${PROGRAMS_TO_REMOVE} ${1}"
}

set_mysql_root_password () {
  echo "Enter the root password to setup mysql with:"
  read MYSQL_ROOT_PASSWORD
  echo "mysql-server mysql-server/root_password select ${MYSQL_ROOT_PASSWORD}" | debconf-set-selections
  echo "mysql-server mysql-server/root_password_again select ${MYSQL_ROOT_PASSWORD}" | debconf-set-selections
}

client () {
  echo "Client"
  install "tuxtype"
  apt-get --assume-yes install $PROGRAMS_TO_INSTALL
  apt-get --assume-yes remove $PROGRAMS_TO_REMOVE
  if [ ! "${UPGRADE_ALL}" = "n" ]; then
    apt-get --assume-yes upgrade
  fi

# Make firefox launch automatically and point it at http://chits_server
  AUTOSTART_DIR=$HOME/.config/autostart
  mkdir --parents $AUTOSTART_DIR
  echo "[Desktop Entry]
Type=Application
Encoding=UTF-8
Version=1.0
Name=No Name
Name[en_US]=Firefox
Comment[en_US]=Firefox
Comment=Firefox
Exec=/usr/bin/firefox -no-remote -P default http://chits_server
X-GNOME-Autostart-enabled=true" > $AUTOSTART_DIR/firefox.desktop

# Create firefox profile with kiosk/fullscreen mode enabled
  wget --output-document=tarlac_firefox_profile.zip http://github.com/mikeymckay/chits/raw/master/install/tarlac_firefox_profile.zip
# unzip this as the user to keep permissions right
  su $SUDO_USER -c "unzip tarlac_firefox_profile.zip"
}

server () {
  echo "Server"
  install "dnsmasq"
  apt-get --assume-yes install $PROGRAMS_TO_INSTALL
  apt-get --assume-yes remove $PROGRAMS_TO_REMOVE
  if [ ! "${UPGRADE_ALL}" = "n" ]; then
    apt-get --assume-yes upgrade
  fi
  wget --output-document=chits_install.sh http://github.com/mikeymckay/chits/raw/master/install/chits_install.sh
  chmod +x chits_install.sh
  ./chits_install.sh
  echo "
# ------------------------------
# Added by tarlac_install script
# ------------------------------
# chits server should be found here
192.168.0.1 chits_server
# ------------------------------
" >> /etc/hosts

# Set static IP
    echo "
auto eth0
iface eth0 inet static
address 192.168.0.1
netmask 255.255.255.0
gateway 192.168.0.1
" > /etc/network/interfaces

# setup DHCP and DNS
# Prepend the following to /etc/dnsmasq.conf
  echo "
# ------------------------------
# Added by tarlac_install script
# ------------------------------
# allow people to query based on hostname
expand-hosts

# Set the domain to be clinic, so http://chits.clinic will resolve, probably not important
domain=clinic

# Provide IP addresses in the range 10-50
dhcp-range=192.168.0.10,192.168.0.50,12h
# ------------------------------

"|cat - /etc/dnsmasq.conf > /tmp/out && mv /tmp/out /etc/dnsmasq.conf

# Handle external DNS resolution - do we want clients to be able to resolve external domains?

  echo "Restarting networking with new IP address (ssh connections may be dropped)"
  /etc/init.d/networking restart
  echo "Starting DCHP Server and DNS Server (dnsmasq)"
  /etc/init.d/dnsmasq restart

}

client_and_server () {
  echo "Client & Server"
  client
  server
}

access_point () {
  echo "Access point"

#TODO!!
# setup gateway with dnsmasq

}

server_and_access_point () {
  server
  access_point
}

client_and_server_and_access_point () {
  server
  client
  access_point
}

#TODO!!
client_with_mysql_replication () {
  set_mysql_root_password
  install "mysql-server"
  client
}

while : # Loop forever
do
cat << !

${PROGRAMS_TO_INSTALL}

1. Client
2. Server
3. Client & Server
4. Server & Access Point
5. Client & Server & Access Point
6. Client with mysql replication
7. Server with mysql replication
8. Exit

!

echo -n " Your choice? : "
read choice

case $choice in
1) client; exit ;;
2) server; exit ;;
3) client_and_server; exit ;;
4) server_and_access_point; exit ;;
5) client_and_server_and_access_point ; exit ;;
6) client_with_mysql_replication; exit ;;
7) server_with_mysql_replication; exit ;;
8) exit ;;
*) echo "\"$choice\" is not valid "; sleep 2 ;;
esac
done

exit
