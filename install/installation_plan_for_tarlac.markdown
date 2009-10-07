#Installation Plan for Tarlac Pilot

Everything that we do must be easy to replicate. Instead of writing out complicated documentation that nobody will ever use, we will write simple documentation that is part of our code base and which heavily relies on scripts to replicate everything that must be done. This document should be updated.

##Types of installation

##client

* Install Netbook Remix (even for HP laptops)
* Uninstall all games, applications, mail clients and lots of other stuff (visual application installer). But leave Firefox
* Install Fullscreen extension for firefox (kiosk mode)
* Set firefox homepage to CHITS
* [client install script](http://github.com/mikeymckay/chits/blob/master/install/TODO)

* Set optimum power settings when running on battery - lowest brightness, CPU scaling, etc.

##server

* install using github script
* [server install script](http://github.com/mikeymckay/chits/blob/master/install/chits_install.sh)

(discuss data security issues, server naming issues, etc)

##mysql replication

* install mysql-server
* setup slave mode replication on all non-masters
* setup master mode replication on master
* [mysql replication master script](http://github.com/mikeymckay/chits/blob/master/install/TODO)
* [mysql replication slave script](http://github.com/mikeymckay/chits/blob/master/install/TODO)

##Ubuntu installation

Language: English
Timezone: Manila
Keyboard layout: USA
Partitions: Use the entire disk
Who are you: chits, chits, password, name 'pc1', 'Log in automatically'

Restart
Eject CD/USB Disk
Applications :: Accessories :: Terminal

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
