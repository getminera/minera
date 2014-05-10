Minera
==============

Minera is a web frontend to monitor the Gridseed devices running with minerd command.

By now Minera supports only the new cpuminer-gc3355 by sandor111 please refer to https://github.com/siklon/cpuminer-gc3355.

Minera is tested only with Gridseed 5chip device, so it could show weird results with Gridseed Blade devices, if you would like to help me support this kind of device, please drop me a line.

Features
-------------

This is a list of some of the Minera's features:

**Dashboard**

* Hashrate widget
* Errors widget
* Last share widget
* Miner details table with per device sort, search, pagination
* Devices tree with cool graph
* Hashrate history
* Errors/Rejects history
* Sysload monitor

**Settings**

* Pools setting with main/failovers (pool are live checked)
* Guided or Manual miner configuration
* Device autotune
* Device autodetection
* Logging
* Start frequency
* Auto-recover mode
* Extra options
* Dashboard refresh time
* Minera password management

Requirements
-------------

Minera should be installed on the Gridseed controller, usually a Raspberry, with a Debian-like Linux distribution and of course an internet connection.

Installing
-------------

You can opt for a img file to put in your SD Card or for a manual install.

**Image file (img)**

The img file is on the go only few days (since today 2014-05-07) and you will be able to download the minera.img and put it in your SD Card without the worry to do anything else but turn on your brand new Minera controller.

**Manual install**

While you are waiting for the img release you can simply install Minera on your current Linux controller. Check if it's a debian based one otherwise download and install a right distribution.
If you have a Raspberry, I suggest you to install Raspbian you can get it here: 

http://downloads.raspberrypi.org/raspbian_latest

Simply download and put it in your SD card (there are tons of guide to do this, google it).

When you have your system ready, ssh into it and **install Minera**

Skip this step and continue below if you have a web server with PHP just installed.

```
sudo apt-get install lighttpd php5-cgi
sudo lighty-enable-mod fastcgi 
sudo lighty-enable-mod fastcgi-php
sudo service lighttpd force-reload
```

When your web server is ready you can install Minera:

```
sudo apt-get install redis-server git screen php5-cli
cd /var/www
sudo git clone https://github.com/michelem09/minera
cd minera
sudo ./install_minera.sh
```

The installer will configure the system requirements and will tell you the URL to connect to.

    Default URL: http://<your-minera-ip>/minera/

	Default password: minera
	
**Important**: minera system user has password "minera", you should change it if your system is a public host with SSH access.

	sudo passwd minera
	
This isn't the web password, to change the web password, login into the web interface and go to *Miner -> Settings*

**Minerd (CPUMiner-gc3355)**

The *minerd* binary path is:

	minera-bin/minerd 
	
It's pre-compiled for Raspberry (ARM) with the latest version available, please refer to https://github.com/siklon/cpuminer-gc3355 if you wanna recompile it.

**For Ubuntu user only**

In the same path you can find also *minerd-ubuntu-64bit* file, this one is pre-compiled for Ubuntu 64bit, you should copy it overwriting *minerd* if you run Minera on Ubuntu.

	cd /var/www/minera/minera-bin
	sudo cp minerd-ubuntu-64bit minerd

Configuring
-------------

Minera has a complete settings page where you can choose many options to start your miners.

You can add or remove pools for failover and select to be guided or to write your own configuration.

Go to settings (*Miner -> Settings*) and choose your preferred options.

A sample settings is pre-configured when you run the install_minera.sh script.

Please remember to change your minera's settings or you will mine for my workers :)

Troubleshooting
-------------

If you wanna check your minera's screen session just SSH into it and attach the session:

	ssh minera@<your-minera-ip>
	screen -r

If you receive the following error:

	Cannot open your terminal '/dev/pts/0' - please check.
	
Please run this commands:

	script /dev/null
	screen -r
	
If you wanna check the raw JSON stats from your minerd, please point your browser to:

	http://<your-minera-ip>/minera/index.php/app/stats
	
If the Minera version upgrade fails you could miss a line in /etc/sudoers file, please check you have this line:

	www-data ALL = (ALL) NOPASSWD: /usr/bin/git
	
If you miss it run this command:

	echo "www-data ALL = (ALL) NOPASSWD: /usr/bin/git" | sudo tee -a /etc/sudoers

TODO
-------------

* Create an img file “plug&play”
* Add some errors control to installer
* Add daily/weekly/monthly charts
* Add more crypto-currencies exchange rates
* Add more system monitor (CPU/Mem)
* Add cgminer/bfgminer support

Screenshots
-------------

Click for hi-res images.

Lockscreen:

[![Dashboard](https://github.com/michelem09/minera/raw/master/assets/img/screen_minera_lock.png)](https://github.com/michelem09/minera/raw/master/assets/img/screen_minera_lock_hi.png)

Dashboard:

[![Dashboard](https://github.com/michelem09/minera/raw/master/assets/img/screen_minera_dashboard.png)](https://github.com/michelem09/minera/raw/master/assets/img/screen_minera_dashboard_hi.png)

Settings:

[![Settings](https://github.com/michelem09/minera/raw/master/assets/img/screen_minera_settings.png)](https://github.com/michelem09/minera/raw/master/assets/img/screen_minera_settings_hi.png)

Donations
-------------

Minera is a free and Open Source software, if you like it, please consider a donation to support it:

    Bitcoin: 1AmREReHNLec9EaW7gLRdW31LNSDA9SGR1

    Litecoin: LLPmAT9gDwmiSdqwWEZu6mpUDmrNAnYBdC

    Dogecoin: DLAHwNxfUTUcePewbkvwvAouny19mcosA7

Thanks and happy mining!

LICENSE
-------------

Copyright 2014 Michele Marcucci

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.