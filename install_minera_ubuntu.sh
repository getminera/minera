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
# sudo ./install_minera_ubuntu.sh
#

echo -e "-----\nInstalling Minera in /var/www\n-----\n"
mkdir /var/www
cd /var/www
git clone https://github.com/getminera/minera
cd minera
./install_minera.sh
