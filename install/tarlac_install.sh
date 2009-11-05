#!/bin/bash
if [ -z "$SUDO_USER" ]; then
    echo "$0 must be called from sudo. Try: 'sudo ${0}'"
    exit 1
fi

echo "Press enter if unsure about the following questions"
echo "Do you want to upgrade all packages? ([y]/n)"
read UPGRADE_ALL

echo "Do you want to remove un-needed packages like games, music players and email? ([y]/n)"
read REMOVE

echo "Do you want to update your apt sources list to remove updates (for a faster install)? ([y]/n)"
read UPDATE_SOURCES

# These are for all configurations
PROGRAMS_TO_INSTALL='openssh-server wget vim'

if [ ! "${REMOVE}" = "n" ]; then
  PROGRAMS_TO_REMOVE="gnome-games gnome-games-data openoffice.org-common f-spot ekiga evolution pidgin totem totem-common brasero rhythmbox synaptic gimp"
fi

if [ ! "${UPDATE_SOURCES}" = "n" ]; then
  sed -i 's/^\(.*updates.*\)$/#\1/' /etc/apt/sources.list
  sed -i 's/^\(.*security.*\)$/#\1/' /etc/apt/sources.list
  apt-get update
fi

echo "
set bell-style none

\"\e[A\": history-search-backward
\"\e[B\": history-search-forward
\"\e[5C\": forward-word
\"\e[5D\": backward-word
\"\e\e[C\": forward-word
\"\e\e[D\": backward-word
\$if Bash
  Space: magic-space
\$endif" > /home/$SUDO_USER/.inputrc


# Call "install wget" to add wget to the list of programs to install
install () {
  PROGRAMS_TO_INSTALL="${PROGRAMS_TO_INSTALL} ${1}"
}

remove () {
  PROGRAMS_TO_REMOVE="${PROGRAMS_TO_REMOVE} ${1}"
}

set_mysql_root_password () {
  if [ ! "$MYSQL_ROOT_PASSWORD" ]; then 
    echo "Enter the root password to setup mysql with:"
    read MYSQL_ROOT_PASSWORD
  fi
  echo "mysql-server mysql-server/root_password select ${MYSQL_ROOT_PASSWORD}" | debconf-set-selections
  echo "mysql-server mysql-server/root_password_again select ${MYSQL_ROOT_PASSWORD}" | debconf-set-selections
  export MYSQL_ROOT_PASSWORD 
}

set_chits_live_password () {
  if [ ! "$CHITS_LIVE_PASSWORD" ]; then 
    echo "Enter password for database user chits_live:"
    read CHITS_LIVE_PASSWORD
  fi
  export CHITS_LIVE_PASSWORD
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
  set_mysql_root_password; 
  set_chits_live_password;

  install "dnsmasq autossh curl"
  apt-get --assume-yes install $PROGRAMS_TO_INSTALL
  apt-get --assume-yes remove $PROGRAMS_TO_REMOVE
  if [ ! "${UPGRADE_ALL}" = "n" ]; then
    apt-get --assume-yes upgrade
  fi
  wget --output-document=chits_install.sh http://github.com/mikeymckay/chits/raw/master/install/chits_install.sh
  wget --output-document=mysql_replication.sh http://github.com/mikeymckay/chits/raw/master/install/mysql_replication.sh
  chmod +x chits_install.sh mysql_replication.sh
  ./chits_install.sh
  echo "Creating ssh keys so we can reverse ssh into the server"
  su $SUDO_USER -c "mkdir /home/$SUDO_USER/.ssh"
  su $SUDO_USER -c "ssh-keygen -N \"\" -f /home/$SUDO_USER/.ssh/id_rsa"

  echo "Setting up reverse autossh to run when network comes up"
  # Generate a random port number to use in the 10000 - 20000 range
  PORT_NUMBER=$[ ( $RANDOM % 10000 )  + 10000 ]
  MONITORING_PORT_NUMBER=$[ ( $RANDOM % 10000 )  + 20000 ]
  echo "#!/bin/sh
# ------------------------------
# Added by tarlac_install script
# ------------------------------
# See autossh and google for reverse ssh tunnels to see how this works

# When this script runs it will allow you to ssh into this machine even if it is behind a firewall or has a NAT'd IP address. 
# From any ssh capable machine you just type ssh -p $PORT_MIDDLEMAN_WILL_LISTEN_ON localusername@middleman

# This is the username on your local server who has public key authentication setup at the middleman
USER_TO_SSH_IN_AS=chitstunnel

# This is the username and hostname/IP address for the middleman (internet accessible server)
MIDDLEMAN_SERVER_AND_USERNAME=chitstunnel@chits.ph

# Port that the middleman will listen on (use this value as the -p argument when sshing)
PORT_MIDDLEMAN_WILL_LISTEN_ON=${PORT_NUMBER}

# Connection monitoring port, don't need to know this one
AUTOSSH_PORT=${MONITORING_PORT_NUMBER}

# Ensures that autossh keeps trying to connect
AUTOSSH_GATETIME=0
su -c "autossh -f -N -R *:${PORT_MIDDLEMAN_WILL_LISTEN_ON}:localhost:22 ${MIDDLEMAN_SERVER_AND_USERNAME} -oLogLevel=error  -oUserKnownHostsFile=/dev/null -oStrictHostKeyChecking=no" $USER_TO_SSH_IN_AS


" > /etc/network/if-up.d/reverse_ssh_tunnel

  chmod +x /etc/network/if-up.d/reverse_ssh_tunnel

  echo "Uploading public key to lakota.vdomck.org"
  PUBLIC_KEY_FILENAME=/tmp/`hostname`.public_key
  cp /home/$SUDO_USER/.ssh/id_rsa.pub $PUBLIC_KEY_FILENAME
  cat "\n#{PORT_NUMBER}" >> $PUBLIC_KEY_FILENAME
  curl -F "file=@${PUBLIC_KEY_FILENAME}" lakota.vdomck.org:4567/upload

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
# Router will be set to 0.2
gateway 192.168.0.2 
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
  set_mysql_root_password; 
  set_chits_live_password;
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
  if [ ! "$MYSQL_ROOT_PASSWORD" ]; then 
    set_mysql_root_password; 
  fi
  install "mysql-server"
  client
  echo "Replication needs to be completed by logging onto the master computer and running the mysql_replication.sh script"
}


#TODO!!
server_with_mysql_replication () {
  server
  wget http://github.com/mikeymckay/chits/raw/master/install/mysql_replication.sh
  chmod +x mysql_replication.sh
  echo "Once your all clients are on the network and ready, run: 'sudo ./mysql_replication'"
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
