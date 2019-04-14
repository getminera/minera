#!/bin/bash
# This is the main install script for RaspiNode https://github.com/piratecash/raspinode
# This script, as RaspiNode, is intended to be used on a Debian-like system

echo -e "-----\nSTART RaspiNode Install script\n-----\n"
cd /var/www/raspinode/

echo -e "-----\nFixing locales\n-----\n"
apt-get update
LANG=en_US.UTF-8
apt-get install -y locales
sed -i -e "s/# $LANG.*/$LANG.UTF-8 UTF-8/" /etc/locale.gen
locale-gen
dpkg-reconfigure --frontend=noninteractive locales
update-locale LANG=$LANG

echo -e "-----\nInstalling extra packages\n-----\n"
DEBIAN_FRONTEND=noninteractive apt-get -yq install build-essential libtool libncurses5-dev autoconf automake redis-server php7.0-cli php7.0-curl php7.0-fpm php7.0-readline php7.0-json shellinabox nginx-light libboost-all-dev libqrencode-dev dh-autoreconf libminiupnpc-dev libgmp-dev python-requests libdb++-dev pwgen python-pip

echo -e "-----\nFIX ISSUE WITH SSL LIBRARY\n-----\n"
apt-get -y remove libssl-dev
sed -i -e "s/stretch/jessie/" /etc/apt/sources.list
apt-get update
apt-get -y install libssl-dev
apt-mark hold libssl-dev
sed -i -e "s/jessie/stretch/" /etc/apt/sources.list
apt-get update

pip install pyfiglet

echo -e "Adding pirate user\n-----\n"
adduser pirate --gecos "" --disabled-password
echo "pirate:piratecash" | chpasswd

echo -e "Adding groups to pirate\n-----\n"
usermod -a -G dialout,plugdev,tty,www-data pirate

echo -e "Adding sudoers configuration for www-data and pirate users\n-----\n"
echo -e "\n#RaspiNode settings\npirate ALL = (ALL) NOPASSWD: ALL\nwww-data ALL = (ALL) NOPASSWD: ALL" >> /etc/sudoers

RASPI_CONF=`pwd`"/conf"

echo -e "Configuring nginx\n-----\n"
cp $RASPI_CONF/nginx /etc/nginx/sites-available/default
service nginx restart

echo -e "Playing with RaspiNode dirs\n-----\n"
chown -R pirate:pirate `pwd`

echo -e "Generation configs\n-----\n"
mkdir -p /home/pirate/.piratecash/
touch /home/pirate/.piratecash/debug.log
chmod 740 /home/pirate/.piratecash/debug.log
chown pirate:www-data /home/pirate/.piratecash/debug.log
chown pirate:pirate /home/pirate/.piratecash
GEN_PASS=`pwgen -1 20 -n`
echo -e "rpcuser=piraterpc\nrpcpassword=${GEN_PASS}\nserver=1\nlisten=1\nmaxconnections=256\ndaemon=1\nrpcallowip=127.0.0.1\ntxindex=1\n" > /home/pirate/.piratecash/piratecash.conf
chown pirate:pirate /home/pirate/.piratecash/piratecash.conf
echo -e "<?php\ndefined('BASEPATH') OR exit('No direct script access allowed');\n\$config['url'] = 'http://piraterpc:${GEN_PASS}@127.0.0.1:11888/';\n\$config['debug'] = FALSE;" > `pwd`/application/config/rpc.php

echo -e "Installing piratecashd\n-----\n"
wget https://github.com/piratecash/piratecash/releases/download/1.0.10/raspberry-piratecashd.tar.gz -O /tmp/raspberry-piratecashd.tar.gz
tar xf /tmp/raspberry-piratecashd.tar.gz -C /usr/local/bin/
su - pirate -c "/usr/local/bin/piratecashd"
sed -i -e "s/exit 0//g" /etc/rc.local
echo -e "su - pirate -c \"/usr/local/bin/piratecashd\"\nexit 0\n" >> /etc/rc.local

echo -e "Adding default startup settings to redis\n-----\n"
echo -n "e0a0bad34f2ac763f60ff775560143e1" | redis-cli -x set raspinode_password
redis-cli save

echo -e "Adding cron file in /etc/cron.d\n-----\n"
echo -e "*/1 * * * * www-data php `pwd`/index.php app cron" > /etc/cron.d/raspinode
echo -e "* * * * * root python /var/www/raspinode/lcd.py > /dev/tty1" > /etc/cron.d/lcd

echo -e "Configuring shellinabox\n-----\n"
cp conf/shellinabox /etc/default/
service shellinabox restart

echo -e 'DONE! RaspiNode is ready!\n\nOpen the URL: http://'$(hostname -I | tr -d ' ')'/raspinode/\n\nAnd happy staking!\n'
