Minera
==============

Minera is a web frontend to monitor the Gridseed devices running with minerd command.

By now Minera supports only the new cpuminer-gc3355 by sandor111 please refer to https://github.com/siklon/cpuminer-gc3355.

Minera is tested only with Gridseed 5chip device, so it could show weird results with Gridseed Blade devices, if you would like to help me support this kind of device, please drop me a line.

Requirements
-------------

Minera should be installed on the Gridseed controller, usually a Raspberry, with a Debian-like Linux distribution.

Installing
-------------

Many softwares like Minera give the complete img file to install on a SD card, I hope I can find time to do that but in the meantime you can start with a fresh Raspbian install if you are on a Raspberry, you can get it here: http://downloads.raspberrypi.org/raspbian_latest

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

Minera needs only to know how to start “minerd” command, there are some options you can choose, so please refer to cpuminer-gc3355 README.

Go to settings (*Miner -> Settings*) and fill the “Minerd options” textarea with something like this:

```
--gc3355=/dev/ttyACM0,/dev/ttyACM1,/dev/ttyACM2 --gc3355-autotune --freq=850 --url=stratum+tcp://<yourpool>:<yourpollport> --userpass=<yourworker>:<yourworkerpass> --retries=1
```

A sample settings is pre-configured when you run the install_minera.sh script.

Please remember to change your minera's settings or you will mine for my workers :)

Troubleshooting
-------------

If you wanna check your minera's screen session just SSH into it and attach the session:

	ssh minera@<your-minera-ip>
	screen -r

If you receive the following error:

	Cannot open your terminal '/dev/pts/0' - please check.
	
Please run:

	script /dev/null
	screen -r
	
If you wanna check the raw JSON stats from your minerd, please point your browser to:

	http://<your-minera-ip>/minera/index.php/app/stats

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

Dashboard:

![Dashboard](https://github.com/michelem09/minera/raw/master/assets/img/screen_minera_dashboard.png)

Settings:

![Settings](https://github.com/michelem09/minera/raw/master/assets/img/screen_minera_settings.png)

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