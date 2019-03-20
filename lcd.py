#!/usr/bin/env python
__author__ = 'hades'

import requests
import json
import datetime

try:
    request = 'http://127.0.0.1/raspinode/index.php/app/lcd'
    response = requests.get(request)
    if response.status_code == 200:
        lcd = json.loads(response.content)
        if not lcd['error']:
            print "\033c\033[1;35m" \
                  ".----. .-..----.   .--.  .---. .----."
            print "| {}  }| || {}  } / {} \{_   _}| {_"
            print "| .--' | || .-. \/  /\  \ | |  | {__"
            print "`-'    `-'`-' `-'`-'  `-' `-'  `----'"
            print "\033[0;37m                               v%s" % lcd['raspinode']
            print
            print "\033[1;32mAvailable:\033[1;37m%20.8f \033[0;37mPIRATE" % lcd['available']
            print "\033[1;32mStake:\033[0;37m%24.8f PIRATE" % lcd['stake']
            print "\033[1;32mTotal:\033[1;37m%24.8f \033[0;37mPIRATE" % (lcd['available'] + lcd['stake'])
            print
            print "\033[1;32mNumber of connections:\033[1;37m%11d" % lcd['connections']
            print "\033[1;32mCurrent number of blocks:\033[1;37m%10d" % lcd['blocks']
            print "\033[1;32mLast block time:\033[1;37m%s" % datetime.datetime.fromtimestamp(
                lcd['last_block_time']).strftime('%Y-%m-%d %H:%M:%S')
            print
            print "Staking info:"
            if lcd['enabled']:
                en = "\033[1;32mOK"
            else:
                en = "\033[1;31mNO"
            if lcd['staking']:
                st = "\033[1;32mOK"
            else:
                st = "\033[1;31mNO"
            print "\033[1;37mEnabled:        %s" % en
            print "\033[1;37mStaking:        %s" % st
            print "\033[1;37mExpected time to earn reward:"
            print "\033[1;35m                %s" % lcd['expected_time']
            print
            print "\033[1;32mWallet:\033[0;37m         %s" % lcd['version']
            print
            print "\033[1;32mTime:\033[1;37m           %s" % datetime.datetime.now().strftime('%Y-%m-%d %H:%M')
except requests.exceptions.ConnectionError:
    pass
except KeyError:
    pass
