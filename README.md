Minera
==============

**[Official Website](http://getminera.com/)**
_______

Minera is a complete system to manage and monitor your bitcoin mining hardware.

Since the 0.3.x version Minera supports the following miner softwares:

* [CPUminer-GC3355 fork](https://github.com/siklon/cpuminer-gc3355)
* [BFGminer latest original version](https://github.com/luke-jr/bfgminer)
* [CGminer latest original version](https://github.com/ckolivas/cgminer)
* [CGminer Dmaxl Zeus fork](https://github.com/dmaxl/cgminer/)

Since the 0.5.x version Minera supports also network miners as:

* Antminer S1 / S3 / S5
* Rockminer
* Any network miner with cgminer
* Other Minera system

Please read more [how to config them here](https://github.com/michelem09/minera/wiki/Network-mining-devices)

If you like it, please consider a donation:

    Bitcoin: 1AmREReHNLec9EaW7gLRdW31LNSDA9SGR1

Thanks.

**[Download from official server (UK) minera-latest.img.zip](http://getminera.com/download/latest)**

**[Download from mirror (MEGA) minera-latest.img.zip](https://mega.nz/#!aRMzkIQK!E0n7hV09QU-ba7vtrJBXBIGWzSwb8Fn8wjmzs3FC7Ws)**

Look below for how to install / how to use it.
_______

Support
-------------

If you need support please try to look at the main [forum thread here](https://bitcointalk.org/index.php?topic=596620.0). If you found a bug, want to propose some changes, want a new feature, please [write down an issue here in Github](https://github.com/michelem09/minera/issues) so we can take everything organised.

* [General support/discussion forum](https://bitcointalk.org/index.php?topic=596620.0)
* [Bugs, issues, requests](https://github.com/michelem09/minera/issues)

_______

Features
-------------

This is a list of some of the Minera's features:

**Dashboard**

* Hashrate widget
* Errors widget
* Last share widget
* Temperature widget
* Main pool widget
* Uptime widget
* Local Miner details table with per device sort, search, pagination
* Local Pools table with on-the-fly pool switch
* Network Miner details table with per device sort, search, pagination
* Network Pools table with on-the-fly pool changes
* Devices tree with cool graph
* Hashrate history
* Errors/Rejects history
* Sysload monitor
* Bitcoin/Crypto currencies rates
* Average hashrate stats
* Web terminal window (like full SSH login)

**Charts**

* Hashrate and Acceptes/Rejected/Errors
* Hourly, daily, monthly, yearly

**Settings**

* Pools setting with main/failovers
* Mobileminer support - Remote monitoring (http://www.mobileminerapp.com/#miners)
* Donations time based
* Guided or Manual miner configuration
* Device autotune
* Device autodetection
* Logging
* Start frequency
* Auto-recover mode
* System timezone
* Extra options
* Scheduled event (reboot/restart)
* Dashboard refresh time
* Minera password management
* Hostname change
* System password change
* Ability ato add custom miner software (like forks)
* Skin selection

_______

Requirements
-------------

Minera should be installed on the mining controller, usually a Raspberry, with a Debian-like Linux distribution and of course an internet connection.

**Note**: You need a decent modern browser, Minera web interface it's NOT tested (and never will be) against IE, so please if you want have the best user experience **DO NOT use Internet Explorer** as browser.
_______

Installing
-------------

You can choose for a img file to put in your SD Card or for a manual install.

**Image file (img) v0.6.1 (Recommended)**

This is the Minera image file for Raspberry PI, you have only to download it and put in your SD Card.

You need at least a 4GB SD Card:

Official United Kingdom server:

> [official-uk] **[Download minera-latest.img.zip](http://getminera.com/download/latest)**

After boot up your controller, point your browser to:

```
http://<your-controller-ip>/minera/
```

Default passwords are "*minera*" you should change them as well as the miner settings.
_______

**Manual install (Some skills needed)**

If you prefer you can simply install Minera on your current Linux controller. Check if it's a debian based one otherwise download and install a right distribution.
If you have a Raspberry, I suggest you to install Raspbian you can get it here:

> http://downloads.raspberrypi.org/raspbian_latest

Simply download and put it in your SD card (there are tons of guide to do this, google it).

When you have your system ready, ssh into it and **install Minera**

Skip this step and continue below if you have a web server with PHP just installed.

```
sudo apt-get install -y lighttpd php5-cgi
sudo lighty-enable-mod fastcgi
sudo lighty-enable-mod fastcgi-php
sudo service lighttpd force-reload
```

When your web server is ready you can install Minera:

```
sudo apt-get install -y redis-server git screen php5-cli php5-curl
cd /var/www
sudo git clone https://github.com/michelem09/minera
cd minera
sudo ./install_minera.sh
```

The installer will configure the system requirements and will tell you the URL to connect to.

    Default URL: http://<your-controller-ip>/minera/

	Default password: minera

**Important**: minera system user has password "minera", you should change it if your system is a public host with SSH access.

	sudo passwd minera

This isn't the web password, to change the web password, login into the web interface and go to *Miner -> Settings*

**Miner command**

The *miner command* binary path is:

	minera-bin/<miner>

They are pre-compiled for Raspberry (ARM) with the latest version available, please refer to each one if you wanna recompile it or use the [build script](#building-miner-software).

**For Ubuntu user only**

If you want use any miner software on Ubuntu (or any other system architecture) you need to compile it and put the binary file in "minera-bin/" directory, pre-built miners are only for ARM architecture.
_______

Upgrading
-------------

Minera will show you a notification icon in the upper right corner if a new version is available. Click the link to run the upgrade.
Your miner will not be stopped, but you should restart it to take full advantages of updates.

If you wanna run and update manually or if you are in trouble and you wanna get a fresh updated code, just SSH into Minera and run these commands:

```
cd /var/www/minera
sudo git fetch --all
sudo git reset --hard origin/master
sudo ./upgrade_minera.sh
```

Your Minera system should be upgraded with the latest commit available.
_______

Configuring
-------------

Minera has a complete settings page where you can choose many options to start your miners.

You can add or remove pools for failover and select to be guided or to write your own configuration.

Go to settings (*Miner -> Settings*) and choose your preferred options.

A sample settings is pre-configured when you run the install_minera.sh script.

Please remember to change your minera's settings or you will mine for my workers :)
_______

Building miner software
-------------

Since version 0.3.6 Minera comes with a script to build updated version of every single miner software.

If you are in trouble with your binary file of Bfgminer for example, you can simply connect in SSH and run this:

```
cd /var/www/minera
./build_miner.sh <miner-name>
```

The script will do everything you need to have the binary file in the correct place and updated.

If you need a list of miner available just run the command without any argument.
_______

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

If you have guided/manual options both selected in the settings page try run this:

	echo -n "1" | redis-cli -x set guided_options
	redis-cli del manual_options

If you wanna check the raw JSON stats from your minerd, please point your browser to:

	http://<your-minera-ip>/minera/index.php/app/stats
	
If you wanna use a new Raspberry PI 2 and you are running a Minera version <= 0.4.0, you need to first upgrade packages from an old Raspberry, then you can use the same SD Card on the new one.
Get a Raspberry PI (not 2), push the Minera SD Card on it, turn on and SSH into it, then run these commands:

	sudo apt-get update
	sudo apt-get upgrade

This could take a while (I mean also an hour, it's slow), but after that you can run your Minera SD Card in any Raspberry PI model, new ones included.
_______

TODO
-------------

* ~~Create an img file “plug&play”~~
* ~~Add some errors control to installer~~
* ~~Add daily/weekly/monthly charts~~
* ~~Add more crypto-currencies exchange rates~~
* Add more system monitor (CPU/Mem)
* ~~Add system temperature~~
* ~~Add cgminer/bfgminer support~~
* Add email notification
* Add start/stop daemon
* ~~Add Mobileminer actions~~
* ~~Add JSON config support~~

_______

Screenshots
-------------

Click for hi-res images.

Lockscreen:

[![Dashboard](https://github.com/michelem09/minera/raw/master/assets/img/screen_minera_lock.png)](https://github.com/michelem09/minera/raw/master/assets/img/screen_minera_lock_hi.png)

Dashboard:

[![Dashboard](https://github.com/michelem09/minera/raw/master/assets/img/screen_minera_dashboard.png)](https://github.com/michelem09/minera/raw/master/assets/img/screen_minera_dashboard_hi.png)

Charts:

[![Charts](https://github.com/michelem09/minera/raw/master/assets/img/screen_minera_charts.png)](https://github.com/michelem09/minera/raw/master/assets/img/screen_minera_charts_hi.png)

Settings:

[![Settings](https://github.com/michelem09/minera/raw/master/assets/img/screen_minera_settings.png)](https://github.com/michelem09/minera/raw/master/assets/img/screen_minera_settings_hi.png)

Web Terminal:

[![Web terminal](https://github.com/michelem09/minera/raw/master/assets/img/screen_minera_terminal.png)](https://github.com/michelem09/minera/raw/master/assets/img/screen_minera_terminal_hi.png)

Blue skin:

[![Blue skin](https://github.com/michelem09/minera/raw/master/assets/img/screen_minera_dashboard_blue.png)](https://github.com/michelem09/minera/raw/master/assets/img/screen_minera_dashboard_blue_hi.png)
_______

Donations
-------------

Minera is a free and Open Source software, if you like it, please consider a donation to support it:

    Bitcoin: 1AmREReHNLec9EaW7gLRdW31LNSDA9SGR1

    Litecoin: LLPmAT9gDwmiSdqwWEZu6mpUDmrNAnYBdC

    Dogecoin: DLAHwNxfUTUcePewbkvwvAouny19mcosA7

Thanks and happy mining!
_______

Credits
-------------
I wish to thank every plugin, libraries, framework, icons, etc authors for their great job, in random order:

* [CPUMiner-GC3355](https://github.com/siklon/cpuminer-gc3355), [BFGminer](https://github.com/luke-jr/bfgminer), [CGminer](https://github.com/ckolivas/cgminer), [CGminer Dmaxl Zeus fork](https://github.com/dmaxl/cgminer/) authors
* [Codeigniter](http://ellislab.com/codeigniter) PHP Framework
* [Raspbian](http://www.raspbian.org/) Raspberry Debian distribution
* [Jquery](http://jquery.com/) Javascript libraries
* [Morris.js](http://www.oesmith.co.uk/morris.js/) for some of the charts
* [Ion Rangeslider](https://github.com/IonDen/ion.rangeSlider) for the sliders
* [AdminLTE](https://github.com/almasaeed2010/AdminLTE) for the awesome HTML/CSS/JS default template
* [Jquery Knob](https://github.com/aterrien/jQuery-Knob) for circled charts
* [Jquery DataTables](https://datatables.net/) for the amazing tables
* [Twitter Bootstrap](http://getbootstrap.com/) for the HTML/CSS theme
* [Font Awesome](http://fortawesome.github.io/Font-Awesome/), [Ion icons](http://ionicons.com/) and [Glyphicon](http://glyphicons.com/) for the stunning well...icons of course :)

_______

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
_______

Join Minera, your brand new [Bitcoin mining system](http://getminera.com)
