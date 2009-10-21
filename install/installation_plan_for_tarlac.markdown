#Installation Plan for Tarlac Pilot

Everything that we do must be easy to replicate. Instead of writing out complicated documentation that nobody will ever use, we will write simple documentation that is part of our code base and which heavily relies on scripts to replicate everything that must be done. This document should be updated.

##Types of installation

All of our computers will be installed from the [tarlac install script](http://github.com/mikeymckay/chits/raw/master/install/tarlac_install.sh) to ensure that they behave in the exact same way. These are the currently available choices:

1. Client
2. Server
3. Client & Server
4. Server & Access Point
5. Client & Server & Access Point
6. Client with mysql replication
7. Server with mysql replication
8. Exit


See [script](http://github.com/mikeymckay/chits/raw/master/install/tarlac_install.sh) for more details

##1. client

* Uninstall all games, applications, mail clients and lots of other stuff (visual application installer). But leave Firefox
* Install Fullscreen extension for firefox (kiosk mode)
* Set firefox homepage to CHITS
* Set optimum power settings when running on battery - lowest brightness, CPU scaling, etc. TODO

##2. server

* install using github script
* [server install script](http://github.com/mikeymckay/chits/blob/master/install/chits_install.sh)

(discuss data security issues, server naming issues, etc)

##mysql replication

* install mysql-server
* setup slave mode replication on all non-masters
* setup master mode replication on master
This is done using the [tarlac_install script](http://github.com/mikeymckay/chits/raw/master/install/tarlac_install.sh) to setup the server and clients, and then the [mysql replication script](http://github.com/mikeymckay/chits/raw/master/install/mysql_replication.sh) once all of the machines are connected and on the same network. Note the importance of using hostnames (which dnsmasq will resolve) and not IP addresses since DHCP is being used.

##Ubuntu installation

* Language: English

* Timezone: Manila

* Keyboard layout: USA

* Partitions: Use the entire disk

* Who are you: chits, chits, password, name 'pc1', 'Log in automatically'

* Restart
* Eject CD/USB Disk
* Applications :: Accessories :: Terminal

    sudo apt-get install wget
    wget http://ow.ly/sPAq
    chmod +x tarlac_install.sh
    sudo ./tarlac_install.sh

When mysql prompts for password use an empty password - just press enter


## TODO
Create checklist for each device

---

###Site 1:
4 Asus EEE 1000HE

* client
* mysql replication
* server

(insert name of person who has used checklist)

###Site 2:
1 HP CQ510 Laptop (server)

* server

2 Asus EEE 1000HE

* client

###Site 3:
3 Asus EEE 1000HE (one of which is a server)

* client
* mysql replication
* server

###Site 4:
4 HP CQ510 (one of which is a server)

 * client
 * mysql replication
 * server
