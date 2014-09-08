#!/bin/bash
# This is the main install script for Minera https://github.com/michelem09/minera
# This script, as Minera, is intended to be used on a Debian-like system

echo -e "-----\nSTART Minera Install script\n-----\n"

echo -e "-----\nInstall extra packages\n-----\n"
apt-get -o Dpkg::Options::="--force-confdef" -o Dpkg::Options::="--force-confold" install -y build-essential libtool libcurl4-openssl-dev libjansson-dev libudev-dev libncurses5-dev autoconf automake postfix redis-server git screen php5-cli php5-curl wicd-curses uthash-dev libmicrohttpd-dev libevent-dev libusb-1.0-0-dev libusb-dev shellinabox

echo -e "Adding Minera user\n-----\n"
adduser minera --gecos "" --disabled-password
echo "minera:minera" | chpasswd

echo -e "Adding groups to Minera\n-----\n"
usermod -a -G dialout,plugdev,tty,www-data minera

echo -e "Adding sudoers configuration for www-data and minera users\n-----\n"
echo -e "\n#Minera settings\nminera ALL = (ALL) NOPASSWD: ALL\nwww-data ALL = (ALL) NOPASSWD: ALL" >> /etc/sudoers

MINER_OPT="--gc3355-detect --gc3355-autotune --freq=850 -o stratum+tcp://multi.ghash.io:3333 -u michelem.minera -p x --retries=1"
MINER_BIN=`pwd`"/minera-bin/"
MINERA_LOGS="/var/log/minera"
MINERA_CONF=`pwd`"/conf"
MINERA_OLD_LOGS=`pwd`"/application/logs"

echo -e "Playing with minera dirs\n-----\n"
chown -R minera.minera `pwd`
mkdir -p $MINERA_LOGS
chmod 777 $MINERA_LOGS
chmod 777 $MINERA_CONF
chmod 777 minera-bin/cgminerStartupScript
chown -R minera.minera $MINERA_LOGS
rm -rf $MINERA_OLD_LOGS
ln -s $MINERA_LOGS $MINERA_OLD_LOGS

echo -e "Adding Minera logrotate\n-----\n"
cp `pwd`"/minera.logrotate" /etc/logrotate.d/minera
service rsyslog restart

echo -e "Adding default startup settings to redis\n-----\n"
echo -n $MINER_OPT | redis-cli -x set minerd_settings
echo -n "minera" | redis-cli -x set minera_password
echo -n "1" | redis-cli -x set guided_options
echo -n "0" | redis-cli -x set manual_options
echo -n "1" | redis-cli -x set minerd_autodetect
echo -n "1" | redis-cli -x set anonymous_stats
echo -n "cpuminer" | redis-cli -x set minerd_software
echo -n '["132","155","3"]' | redis-cli -x set dashboard_coin_rates
echo -e '[{"url":"stratum+tcp://multi.ghash.io:3333","username":"michelem.minera","password":"x"}]'  | redis-cli -x set minerd_pools

echo -e "Adding minera startup command to rc.local\n-----\n"
chmod 777 /etc/rc.local

RC_LOCAL_CMD='su - minera -c "/usr/bin/screen -dmS cpuminer '$MINER_BIN'minerd '$MINER_OPT'"\nexit 0'

sed -i.bak "s/exit 0//g" /etc/rc.local

echo -e $RC_LOCAL_CMD >> /etc/rc.local

echo -e "Adding cron file in /etc/cron.d\n-----\n"

echo -e "*/1 * * * * www-data php `pwd`/index.php app cron" > /etc/cron.d/minera

echo -e "Configuring shellinabox\n-----\n"
sudo cp conf/shellinabox /etc/default/
sudo service shellinabox restart

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
	sudo ldconfig
fi

echo -e "Generating unique SSH keys\n-----\n"
sudo rm /etc/ssh/ssh_host_*
sudo dpkg-reconfigure openssh-server
sudo service ssh restart

echo -e 'DONE! Minera is ready!\n\nOpen the URL: http://'$(hostname -I | tr -d ' ')'/minera/\n\nAnd happy mining!\n'
