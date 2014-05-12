#!/bin/bash
# This is the upgrade script for Minera https://github.com/michelem09/minera
# This script, as Minera, is intended to be used on a Debian-like system

echo -e "-----\nSTART Minera Upgrade script\n-----\n"

echo -e "-----\nInstall extra packages\n-----\n"
apt-get install -y build-essential libtool libcurl4-openssl-dev libjansson-dev libudev-dev libncurses5-dev autoconf automake postfix

MINERA_LOGS="/var/log/minera"

echo -e "Changing permissions on Minera dir\n-----\n"
chown -R minera.minera `pwd`
mkdir -p $MINERA_LOGS
chmod 777 $MINERA_LOGS
chown -R minera.minera $MINERA_LOGS

echo -e "Upgrading sudoers configuration for www-data and minera users\n-----\n"
echo -e "\n#Minera settings\nminera ALL=(ALL) NOPASSWD: ALL\nwww-data ALL = (ALL) NOPASSWD: ALL" >> /etc/sudoers

echo -e "Changing cron file\n-----\n"
echo "*/5 * * * * www-data php `pwd`/index.php app cron" > /etc/cron.d/minera

echo -e "Adding minera to www-data group\n-----\n"
usermod -a -G www-data minera

echo -e "Adding default settings\n-----\n"
echo -n "1" | redis-cli -x set guided_options

echo -e 'DONE! Minera is ready!\n\nOpen the URL: http://'$(hostname -I | tr -d ' ')'/minera/\n\nAnd happy mining!\n'
