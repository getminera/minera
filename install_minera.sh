#!/bin/bash
# This is the main install script for Minera https://github.com/michelem09/minera
# This script, as Minera, is intended to be used on a Debian-like system

echo -e "-----\nSTART Minera Install script\n-----\n"

echo -e "Adding Minera user\n-----\n"
adduser minera --gecos "" --disabled-password
echo "minera:minera" | chpasswd

echo -e "Adding groups to Minera\n-----\n"
usermod -a -G dialout,plugdev,netdev,input minera

echo -e "Adding sudoers configuration for www-data and minera users\n-----\n"
echo -e "\n#Minera settings\nminera ALL=(ALL) NOPASSWD: ALL\nwww-data ALL = (ALL) NOPASSWD: /bin/kill\nwww-data ALL = (ALL) NOPASSWD: /usr/bin/screen\nwww-data ALL = (ALL) NOPASSWD: /sbin/reboot\nwww-data ALL = (ALL) NOPASSWD: /sbin/shutdown\nwww-data ALL = (minera) NOPASSWD: /usr/bin/killall" >> /etc/sudoers

NEW_UUID=$(cat /dev/urandom | tr -dc 'a-z0-9' | fold -w 12 | head -n 1)
MINER_OPT="--gc3355=/dev/ttyACM0 --gc3355-autotune --freq=850 --url=stratum+tcp://doge.ghash.io:3333 --userpass=michelem.$NEW_UUID:x --retries=1"
MINER_BIN=`pwd`"/minera-bin/"

echo -e "Adding default startup settings to redis\n-----\n"
echo -n $MINER_OPT | redis-cli -x set minerd_settings

echo -e "Adding minera startup command to rc.local\n-----\n"
chmod 777 /etc/rc.local

RC_LOCAL_CMD='su - minera -c "/usr/bin/screen -dmS cpuminer '$MINER_BIN'minerd '$MINER_OPT'"\nexit 0'

sed -i.bak "s/exit 0//g" /etc/rc.local

echo -e $RC_LOCAL_CMD >> /etc/rc.local

echo -e "Adding cron file in /etc/cron.d\n-----\n"

echo "*/5 * * * * minera php `pwd`/index.php app cron_stats" > /etc/cron.d/minera

echo -e "DONE! Minera is ready!\n\nOpen the URL: http://$(hostname -I)/minera/\n\nAnd happy mining!\n"
