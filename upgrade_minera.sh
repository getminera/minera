#!/bin/bash
# This is the upgrade script for Minera https://github.com/michelem09/minera
# This script, as Minera, is intended to be used on a Debian-like system

echo -e "-----\nSTART Minera Upgrade script\n-----\n"

echo -e "-----\nInstall extra packages\n-----\n"
#apt-get update
#export DEBIAN_FRONTEND=noninteractive
apt-get -o Dpkg::Options::="--force-confdef" -o Dpkg::Options::="--force-confold" install -y build-essential libtool libcurl4-openssl-dev libjansson-dev libudev-dev libncurses5-dev autoconf automake postfix redis-server git screen php5-cli php5-curl uthash-dev libmicrohttpd-dev libevent-dev libusb-1.0-0-dev libusb-dev shellinabox

sudo dpkg --configure -a

MINERA_LOGS="/var/log/minera"
MINERA_CONF=`pwd`"/conf"
MINERA_OLD_LOGS=`pwd`"/application/logs"

echo -e "Changing permissions on Minera dir\n-----\n"
chown -R minera.minera `pwd`
mkdir -p $MINERA_LOGS
chmod 777 $MINERA_LOGS
chmod -R 777 $MINERA_CONF
chmod 777 minera-bin/cgminerStartupScript
chown -R minera.minera $MINERA_LOGS
chown -R www-data.www-data $MINERA_LOGS/log*.php
rm -rf $MINERA_OLD_LOGS
ln -s $MINERA_LOGS $MINERA_OLD_LOGS

echo -e "Adding Minera logrotate\n-----\n"
cp `pwd`"/minera.logrotate" /etc/logrotate.d/minera
service rsyslog restart

echo -e "Upgrading sudoers configuration for www-data and minera users\n-----\n"
if ! grep -q 'www-data ALL = (ALL) NOPASSWD: ALL' '/etc/sudoers'; then
	echo -e "www-data ALL = (ALL) NOPASSWD: ALL" >> /etc/sudoers
fi
if ! grep -q 'minera ALL = (ALL) NOPASSWD: ALL' '/etc/sudoers'; then
	echo -e "minera ALL = (ALL) NOPASSWD: ALL" >> /etc/sudoers
fi

echo -e "Configuring shellinabox\n-----\n"
sudo cp conf/shellinabox /etc/default/
sudo service shellinabox restart

echo -e "Changing cron file\n-----\n"
echo -e "*/1 * * * * www-data php `pwd`/index.php app cron" > /etc/cron.d/minera

echo -e "Adding minera to www-data group\n-----\n"
usermod -a -G www-data minera

# This doesn't need here, check on PHP scripts for existence
#echo -e "Adding default settings\n-----\n"
#echo -n "1" | redis-cli -x set guided_options
#echo -n "1" | redis-cli -x set anonymous_stats
#echo -n "cpuminer" | redis-cli -x set minerd_software
#echo -n '["132","155","3"]' | redis-cli -x set dashboard_coin_rates

echo -e "Update redis values\n-----\n"
redis-cli del minera_update
redis-cli del minera_version

echo -e "Copying cg/bfgminer udev rules\n-----\n"
sudo cp conf/01-cgminer.rules /etc/udev/rules.d/
sudo cp conf/70-bfgminer.rules /etc/udev/rules.d/
sudo service udev restart

echo -e "Installing libblkmaker\n-----\n"
LIBCOUNT=`strings -n5 /etc/ld.so.cache|grep -i libblkmaker|wc -l`
if [ $LIBCOUNT -lt 2 ];
then
	cd minera-bin/src/libblkmaker
	sudo make install
fi
sudo ldconfig

echo -e 'DONE! Minera is ready!\n\nOpen the URL: http://'$(hostname -I | tr -d ' ')'/minera/\n\nAnd happy mining!\n'
