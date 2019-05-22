#!/bin/bash
# This is the upgrade script for RaspiNode https://github.com/piratecash/raspinode
# This script, as RaspiNode, is intended to be used on a Debian-like system

echo -e "-----\nSTART RaspiNode Upgrade script\n-----\n"

echo -e "-----\nInstall extra packages\n-----\n"

apt-get -o Dpkg::Options::="--force-confdef" -o Dpkg::Options::="--force-confold" install build-essential libtool libncurses5-dev autoconf automake redis-server php7.0-cli php7.0-curl php7.0-fpm php7.0-readline php7.0-json shellinabox nginx-light libboost-all-dev libqrencode-dev dh-autoreconf libminiupnpc-dev libgmp-dev python-requests libdb++-dev pwgen python-pip

sudo dpkg --configure -a

pip install pyfiglet

echo -e "Upgrading sudoers configuration for www-data and minera users\n-----\n"

if ! grep -q 'www-data ALL = (ALL) NOPASSWD: ALL' '/etc/sudoers'; then
        echo -e "www-data ALL = (ALL) NOPASSWD: ALL" >> /etc/sudoers
fi

if ! grep -q 'pirate ALL = (ALL) NOPASSWD: ALL' '/etc/sudoers'; then
        echo -e "pirate ALL = (ALL) NOPASSWD: ALL" >> /etc/sudoers
fi

echo -e "Configuring shellinabox\n-----\n"
sudo cp conf/shellinabox /etc/default/
sudo service shellinabox restart

echo -e "Changing cron file\n-----\n"
echo -e "*/1 * * * * www-data php `pwd`/index.php app cron" > /etc/cron.d/raspinode
echo -e "* * * * * root python /var/www/raspinode/lcd.py > /dev/tty1" > /etc/cron.d/lcd

echo -e "Adding groups to pirate\n-----\n"
usermod -a -G dialout,plugdev,tty,www-data pirate

echo -e "Update redis values\n-----\n"
redis-cli del raspinode_update
redis-cli del raspinode_version
redis-cli del altcoins_update
redis-cli del dashboard_coin_rates

echo -e "Update piratecashd\n-----\n"
su - pirate -c "/usr/local/bin/piratecashd stop"
sleep 10
wget https://github.com/piratecash/piratecash/releases/download/v11-release/raspberry-piratecashd.tar.gz -O /tmp/raspberry-piratecashd.tar.gz
rm /usr/local/bin/piratecashd
tar xf /tmp/raspberry-piratecashd.tar.gz -C /usr/local/bin/
su - pirate -c "/usr/local/bin/piratecashd"

echo -e 'DONE! RaspiNode is ready!\n\nOpen the URL: http://'$(hostname -I | tr -d ' ')'/raspinode/\n\nAnd happy staking!\n'
