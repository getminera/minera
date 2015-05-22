#! /bin/bash
#
# Thanks to Michel Daggelinckx 
# @nightrid3r (https://github.com/nightrid3r)
#
# This script should be run from a Debian/Ubuntu based system
#
# It installs all the dependencies, clones the latest master code for Minera, 
# installs Minera itself and finally it builds every available built-in miners (CPUMiner, Bfgminer, etc...)
#
# Run it as your personal user (minera user will be added to your system)
#
# ./install_minera_ubuntu.sh
#

echo -e "-----\nInstall DEB packages\n-----\n"
sudo apt-get install -y lighttpd php5-cgi redis-server git screen php5-cli php5-curl

echo -e "-----\nConfiguring Lighttpd\n-----\n"
sudo lighty-enable-mod fastcgi
sudo lighty-enable-mod fastcgi-php
sudo service lighttpd force-reload

echo -e "-----\nInstalling Minera in /var/www\n-----\n"
cd /var/www
sudo git clone https://github.com/michelem09/minera
cd minera
sudo ./install_minera.sh
./build_miner.sh all