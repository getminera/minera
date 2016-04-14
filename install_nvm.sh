#!/bin/bash

cd /var/www/minera
curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.31.0/install.sh | bash
. /home/minera/.nvm/nvm.sh
nvm install 4
sudo cp conf/node-server.conf /etc/supervisor/conf.d/
cd /var/www/minera/server
npm install
sudo service supervisor restart
exit