#!/bin/bash
# This is the main install script for Minera https://github.com/michelem09/minera
# This script, as Minera, is intended to be used on a Debian-like system

echo -e "-----\nSTART Minera Install script\n-----\n"

echo -e "Adding Minera user\n-----\n"
adduser minera --gecos "" --disabled-password
echo "minera:minera" | chpasswd

echo -e "Adding groups to Minera\n-----\n"
usermod -a -G dialout,plugdev,tty,www-data minera

echo -e "Adding sudoers configuration for www-data and minera users\n-----\n"
echo -e "\n#Minera settings\nminera ALL=(ALL) NOPASSWD: ALL\nwww-data ALL = (ALL) NOPASSWD: /bin/kill\nwww-data ALL = (ALL) NOPASSWD: /usr/bin/screen\nwww-data ALL = (ALL) NOPASSWD: /sbin/reboot\nwww-data ALL = (ALL) NOPASSWD: /sbin/shutdown\nwww-data ALL = (ALL) NOPASSWD: /usr/bin/killall" >> /etc/sudoers

MINER_OPT="--gc3355-detect --gc3355-autotune --freq=850 -o stratum+tcp://multi.ghash.io:3333 -u michelem.minera -p x --retries=1"
MINER_BIN=`pwd`"/minera-bin/"

echo -e "Chown minera dir\n-----\n"
chown -R minera.minera `pwd`
chmod -R 777 `pwd`/application/logs

echo -e "Adding default startup settings to redis\n-----\n"
echo -n $MINER_OPT | redis-cli -x set minerd_settings
echo -n "minera" | redis-cli -x set minera_password
echo -n "1" | redis-cli -x set guided_options
echo -n "1" | redis-cli -x set minerd_autodetect
echo -e '[{"url":"stratum+tcp://multi.ghash.io:3333","username":"michelem.minera","password":"x"}]'  | redis-cli -x set minerd_pools

echo -e "Adding minera startup command to rc.local\n-----\n"
chmod 777 /etc/rc.local

RC_LOCAL_CMD='su - minera -c "/usr/bin/screen -dmS cpuminer '$MINER_BIN'minerd '$MINER_OPT'"\nexit 0'

sed -i.bak "s/exit 0//g" /etc/rc.local

echo -e $RC_LOCAL_CMD >> /etc/rc.local

echo -e "Adding cron file in /etc/cron.d\n-----\n"

echo "*/5 * * * * www-data php `pwd`/index.php app cron" > /etc/cron.d/minera

echo -e 'DONE! Minera is ready!\n\nOpen the URL: http://'$(hostname -I | tr -d ' ')'/minera/\n\nAnd happy mining!\n'
