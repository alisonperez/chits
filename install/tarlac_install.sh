#!/bin/sh
if [ -z "$SUDO_USER" ]; then
    echo "$0 must be called from sudo"
    exit 1
fi

# These are for all configurations
PROGRAMS_TO_INSTALL='openssh-server wget'
PROGRAMS_TO_REMOVE="gnome-games gnome-games-data openoffice.org-common f-spot ekiga evolution pidgin totem totem-common brasero rhythmbox synaptic gimp"

# Call "install wget" to add wget to the list of programs to install
install() {
  PROGRAMS_TO_INSTALL="${PROGRAMS_TO_INSTALL} ${1}"
}

remove() {
  PROGRAMS_TO_REMOVE="${PROGRAMS_TO_INSTALL} ${1}"
}

client () {
  echo "Client"
  install "tuxtype"
  apt-get --assume-yes install $PROGRAMS_TO_INSTALL
  apt-get --assume-yes remove $PROGRAMS_TO_REMOVE
  apt-get --assume-yes upgrade

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
  wget http://github.com/mikeymckay/chits/raw/master/install/tarlac_firefox_profile.zip
# unzip this as the user to keep permissions right
  su $SUDO_USER -c "unzip tarlac_firefox_profile.zip"
}

server () {
  echo "Server"
  install "dnsmasq"
  apt-get --assume-yes install $PROGRAMS_TO_INSTALL
  apt-get --assume-yes remove $PROGRAMS_TO_REMOVE
  apt-get --assume-yes upgrade
  wget http://github.com/mikeymckay/chits/raw/master/install/chits_install.sh
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

  /etc/init.d/mysql restart
  /etc/init.d/networking restart
  /etc/init.d/dnsmasq restart

}

client_and_server () {
  echo "Client & Server"
  server
  client
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
