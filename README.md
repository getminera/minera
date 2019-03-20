RaspiNode
==============

Raspinode is a complete system to manage and monitor your PirateCash mining process.

Requirements
-------------

RaspiNode should be installed on the mining controller, usually a Raspberry, with a Debian-like Linux distribution and of course an internet connection.

**Note**: You need a decent modern browser, Minera web interface it's NOT tested (and never will be) against IE, so please if you want have the best user experience **DO NOT use Internet Explorer** as browser.

Installing
-------------

If you have a Raspberry, I suggest you to install Raspbian you can get it here:

> http://downloads.raspberrypi.org/raspbian_latest

Simply download and put it in your SD card (there are tons of guide to do this, google it).

When you have your system ready, ssh into it and **install Raspinode**

You can use this script to install everything you need, (it's good for any kind of Debian system: Ubuntu, Raspbian, ecc...) or use the manual steps below:

```
sudo apt-get install -y git
sudo mkdir /var/www
cd /var/www
sudo git clone https://github.com/piratecash/raspinode
cd raspinode
sudo ./install_raspinode.sh
```

The installer will configure the system requirements and will tell you the URL to connect to.

    Default URL: http://<your-controller-ip>/raspinode/

    Default password: piratecash

**Important**: pirate system user has password "piratecash", you should change it if your system is a public host with SSH access.

    sudo passwd pirate

This isn't the web password, to change the web password, login into the web interface and go to *Pirate -> Settings*

Upgrading
-------------

Raspinode will show you a notification icon in the upper right corner if a new version is available. Click the link to run the upgrade.
Your node will not be stopped, but you should restart it to take full advantages of updates.

If you wanna run and update manually or if you are in trouble and you wanna get a fresh updated code, just SSH into Pirate and run these commands:

```
cd /var/www/raspinode
sudo git fetch --all
sudo git reset --hard origin/master
sudo ./upgrade_raspinode.sh
```
