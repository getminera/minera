Dillinger
Local Files 
Dropbox 
GitHub 
Google Drive 
Utilities 
Select Your Theme 

15
16
17
18
19
20
21
22
23
24
25
26
27
28
29
30
31
32
33
34
35
36
37
38
39
40
41
42
43
44
45
Installing
-------------
Many softwares like Minera give the complete img file to install on a SD 
card, I hope I can find time to do that but in the meantime you can start 
with a fresh Raspbian install if you are on a Raspberry, you can get it 
here: http://downloads.raspberrypi.org/raspbian_latest
Simply download and put it in your SD card (there are tons of guide to do 
this, google it).
When you have your system ready, ssh into it and **install Minera:**
```
sudo apt-get install nginx php5 php5-cli php5-fpm redis
cd /var/www
sudo git clone https://github.com/michelem09/minera
sudo ./install_minera.sh
```
The installer will configure the system requirements and will tell you the 
URL to connect to.
    Default URL: http://<your-minera-ip>/minera/
    Default password: minera
Configuring
-------------
Minera needs only to know how to start “minerd” command, there are some 
options you can choose, so please refer to cpuminer-gc3355 README.
Go to settings (Sidebar -> Miner -> Settings) and fill the “Minerd options” 
textarea with something like this:
```
Untitled Document  Words: 415 
Minera
Minera is a web frontend to monitor the Gridseed devices running with minerd command.

By now Minera supports only the new cpuminer-gc3355 by sandor111 please refer to https://github.com/siklon/cpuminer-gc3355.

Minera is tested only with Gridseed 5chip device, so it could show weird results with Gridseed Blade devices, if you would like to help me support this kind of device, please drop me a line.

Requirements
Minera should be installed on the Gridseed controller, usually a Raspberry, with a Debian-like Linux distribution.

Installing
Many softwares like Minera give the complete img file to install on a SD card, I hope I can find time to do that but in the meantime you can start with a fresh Raspbian install if you are on a Raspberry, you can get it here: http://downloads.raspberrypi.org/raspbian_latest

Simply download and put it in your SD card (there are tons of guide to do this, google it).

When you have your system ready, ssh into it and install Minera:

sudo apt-get install nginx php5 php5-cli php5-fpm redis
cd /var/www
sudo git clone https://github.com/michelem09/minera
sudo ./install_minera.sh
The installer will configure the system requirements and will tell you the URL to connect to.

Default URL: http://<your-minera-ip>/minera/

Default password: minera
Configuring
Minera needs only to know how to start “minerd” command, there are some options you can choose, so please refer to cpuminer-gc3355 README.

Go to settings (Sidebar -> Miner -> Settings) and fill the “Minerd options” textarea with something like this:

--gc3355=/dev/ttyACM0,/dev/ttyACM1,/dev/ttyACM2 --gc3355-autotune --freq=850 --url=stratum+tcp://<yourpool>:<yourpollport> --userpass=<yourworker>:<yourworkerpass> --retries=1
A sample settings is pre-configured when you run the install_minera.sh script, please remember to change your minera's settings or you will mine for my workers :)

TODO
Create an img file “plug&play”
Add some errors control
Add daily/weekly/monthly charts
Add more crypto-currencies exchange rates
Add more system monitor (CPU/Mem)
Add cgminer/bfgminer support
Donations
Minera is a free and Open Source software, if you like it, please consider a donation to support it:

Bitcoin: 1AmREReHNLec9EaW7gLRdW31LNSDA9SGR1

Litecoin: LLPmAT9gDwmiSdqwWEZu6mpUDmrNAnYBdC

Dogecoin: DLAHwNxfUTUcePewbkvwvAouny19mcosA7
Thanks and happy mining!

LICENSE
Copyright 2014 Michele Marcucci

Licensed under the Apache License, Version 2.0 (the "License"); you may not use this file except in compliance with the License. You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0
Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.