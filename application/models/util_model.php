<?php

/*
 * Utit_model
 * Utility model for minera
 *
 * @author michelem
 */

class Util_model extends CI_Model {

    private $_minerdSoftware;

    public function __construct() {
        // load Miner Model
        parent::__construct();
    }

    public function isLoggedIn() {
        $storedp = $this->redis->get('raspinode_password');

        if ($this->session->userdata("loggedin") !== $storedp) {
            redirect('app/index');
            return false;
        }

        return true;
    }

    /*
      //
      // Stats related stuff
      //
     */

    // Get the live stats from miner
    public function getStats() {
        $date = new DateTime();
        $a = new stdClass();
        $altcoinData = array("error" => "true");
        $btcData = $this->getBtcUsdRates();

        if ($this->isOnline()) {
            $miner = $this->getMinerStats();
            $a->piratecashd = $miner;
        } else {
            $a->notrunning = true;
        }
        $uptime = @file_get_contents("/proc/uptime");
        $uptime = explode(" ", $uptime);
        $uptime = $uptime[0];
        $a->start_time = time() - $uptime;

        $a->localweight = $this->redis->get("localweight_weight") == "" ?
                "N/A" : $this->redis->get("localweight_weight");

        $a->devices = [
            '127.0.0.1' => [
                'temperature' => $this->checkTemp()['value'],
                'serial' => exec("cat /proc/cpuinfo |grep Serial|cut -d' ' -f2"),
                'hash' => 10000,
                'accepted' => $this->redis->get("node_accepted") == "" ?
                0 : $this->redis->get("node_accepted"),
                'rejected' => 0,
                'frequency' => 155,
                'localweight' => $a->localweight,
                'last_share' => $this->redis->get("node_last_accepted_time") == "" ?
                0 : $this->redis->get("node_last_accepted_time")
            ]
        ];
        $a->totals = [
            'accepted' => $this->redis->get("node_accepted") == "" ?
            0 : $this->redis->get("node_accepted"),
            'rejected' => 0,
            'localweight' => $a->localweight,
            'last_share' => $this->redis->get("node_last_accepted_time") == "" ?
            0 : $this->redis->get("node_last_accepted_time")
        ];

        $a->weight = $this->redis->get("network_weight") == "" ?
                "N/A" : $this->redis->get("network_weight");

        $a->localweight = $this->redis->get("localweight_weight") == "" ?
                "N/A" : $this->redis->get("localweight_weight");

        // Add miner software used
        $a->miner = $this->_minerdSoftware;

        //log_message("error", var_export($netStats, true));
        // Add sysload stats
        $a->sysload = sys_getloadavg();

        // Add cron status
        $a->cron = $this->redis->get("cron_lock");

        // Add sysuptime
        $a->sysuptime = $this->getSysUptime();

        // Add controller temp
        $a->temp = $this->checkTemp();

        // Add BTC rates
        $a->btc_rates = $btcData;

        // Add AltCoin rates
        $a->altcoins_rates = $altcoinData;

        // Add average stats
        $a->avg = $this->getStoredAvgStats();

        // Add coins profitability
        $a->profits = json_decode($this->redis->get('coins_profitability'), true, 512, JSON_BIGINT_AS_STRING);

        $a->livestat = true;

        $a->timestamp = $date->getTimestamp();

        // Publish stats to Redis
        $this->redis->publish("minera-channel", json_encode($a));

        return json_encode($a);
    }

    // Get the specific miner stats
    public function getMinerStats() {
        if ($this->isOnline()) {
            $this->load->model('pirate_model', 'miner');
            $result = [];
            $result['staking'] = $this->rpc->getstakinginfo();
            return $result;
        } else {
            return false;
        }
        return false;
    }

    /*
      // Parse the miner stats to add devices
      // with a summary total and active pool
     */

    public function getParsedStats($stats) {
        $d = 0;
        $tdevice = array();
        $tdtemperature = 0;
        $tdfrequency = 0;
        $tdaccepted = 0;
        $tdrejected = 0;
        $tdhwerrors = 0;
        $tdshares = 0;
        $tdhashrate = 0;
        $devicePoolActives = false;
        $return = false;

        if (isset($stats->start_time)) {
            $return['start_time'] = $stats->start_time;
        } elseif (isset($stats->summary[0]->SUMMARY[0]->Elapsed)) {
            $return['start_time'] = round((time() - $stats->summary[0]->SUMMARY[0]->Elapsed), 0);
        }

        if (isset($stats->err)) {
            $return['err'] = $stats->err;
        }

        $poolHashrate = 0;

        // piratecash daemon stats
        if (isset($stats->devices)) {
            foreach ($stats->devices as $name => $device) {
                $d++;
                $c = 0;
                $tcfrequency = 0;
                $tcaccepted = 0;
                $tcrejected = 0;
                $tchwerrors = 0;
                $tcshares = 0;
                $tchashrate = 0;
                $tclastshares = array();

                if ($device->chips) {
                    foreach ($device->chips as $chip) {
                        $c++;
                        $tcfrequency += $chip->frequency;
                        $tcaccepted += $chip->accepted;
                        $tcrejected += $chip->rejected;
                        $tchwerrors += $chip->hw_errors;
                        $tcshares += $chip->shares;
                        $tchashrate += $chip->hashrate;
                        $tclastshares[] = $chip->last_share;
                    }
                }

                $return['devices'][$name]['temperature'] = false;
                $return['devices'][$name]['frequency'] = ($c > 0) ? round(($tcfrequency / $c), 0) : 0;
                $return['devices'][$name]['accepted'] = $tcaccepted;
                $return['devices'][$name]['rejected'] = $tcrejected;
                $return['devices'][$name]['hw_errors'] = $tchwerrors;
                $return['devices'][$name]['shares'] = $tcshares;
                $return['devices'][$name]['hashrate'] = $tchashrate;
                $return['devices'][$name]['last_share'] = (count($tclastshares) > 0) ? max($tclastshares) : 0;
                $return['devices'][$name]['serial'] = (isset($device->serial)) ? $device->serial : false;

                $tdfrequency += $return['devices'][$name]['frequency'];
                $tdaccepted += $return['devices'][$name]['accepted'];
                $tdrejected += $return['devices'][$name]['rejected'];
                $tdhwerrors += $return['devices'][$name]['hw_errors'];
                $tdshares += $return['devices'][$name]['shares'];
                $tdhashrate += $return['devices'][$name]['hashrate'];
                $tdlastshares[] = $return['devices'][$name]['last_share'];
            }

            $return['totals']['temperature'] = false;
            $return['totals']['frequency'] = round(($tdfrequency / $d), 0);
            $return['totals']['accepted'] = $tdaccepted;
            $return['totals']['rejected'] = $tdrejected;
            $return['totals']['hw_errors'] = $tdhwerrors;
            $return['totals']['shares'] = $tdshares;
            $return['totals']['hashrate'] = $tdhashrate;
            $return['totals']['last_share'] = max($tdlastshares);
        }

        return json_encode($return);
    }

    // Get the stored stats from Redis
    public function getStoredAvgStats() {
        $periods = array("1min" => 60, "5min" => 300, "1hour" => 3600, "1day" => 86400);

        foreach ($periods as $period => $seconds) {
            if ($seconds == 60)
                $rows = $this->redis->command("ZREVRANGE piratecashd_delta_stats 0 1");
            else
                $rows = $this->redis->command("ZREVRANGE piratecashd_avg_stats_$seconds 0 1");

            $avgs[$period] = array();
            if (count($rows) > 0) {
                foreach ($rows as $row) {
                    $row = json_decode($row);
                    $avgs[$period][] = $row;
                }
            }
        }

        return $avgs;
    }

    // Get the stored stats from Redis
    public function getStoredStats($seconds = 3600, $startTime = false, $avg = false) {
        $current = ($startTime) ? $startTime : time();
        $startTime = $current - $seconds;

        if ($avg) {
            $o = $this->redis->command("ZRANGEBYSCORE piratecashd_avg_stats_$avg $startTime $current");
        } else {
            $o = $this->redis->command("ZRANGEBYSCORE piratecashd_delta_stats $startTime $current");
        }

        return $o;
    }

    // Get the stored stats from Redis
    public function getHistoryStats($type = "hourly") {
        switch ($type) {
            case "hourly":
                $period = 300;
                $range = 12;
                $avg = false;
                break;
            case "daily":
                $period = 3600;
                $range = 24;
                $avg = 300;
                break;
            case "weekly":
                $period = 3600 * 24;
                $range = 7;
                $avg = 3600;
                break;
            case "monthly":
                $period = 3600 * 24;
                $range = 30;
                $avg = 3600;
                break;
            case "yearly":
                $period = 3600 * 24 * 14;
                $range = 27;
                $avg = 86400;
                break;
        }

        $items = array();

        for ($i = 0; $i <= ($range * $period); $i += $period) {
            $statTime = (time() - $i);
            $item = json_decode($this->avgStats($period, $statTime, $avg));
            if ($item)
                $items[] = $item;
        }

        $o = json_encode($items);

        return $o;
    }

    public function avgStats($seconds = 900, $startTime = false, $avg = false) {
        $records = $this->getStoredStats($seconds, $startTime, $avg);

        $i = 0;
        $timestamp = 0;
        $netweight = 0;
        $weight = 0;
        $frequency = 0;
        $accepted = 0;
        $errors = 0;
        $rejected = 0;
        $shares = 0;

        if (count($records) > 0) {
            foreach ($records as $record) {
                $i++;
                $obj = json_decode($record);
                $timestamp += (isset($obj->timestamp)) ? $obj->timestamp : 0;
                $netweight += (isset($obj->net_weight)) ? $obj->net_weight : 0;
                $weight += (isset($obj->weight)) ? $obj->weight : 0;
                $frequency += (isset($obj->avg_freq)) ? $obj->avg_freq : 0;
                $accepted += (isset($obj->accepted)) ? $obj->accepted : 0;
                $errors += (isset($obj->errors)) ? $obj->errors : 0;
                $rejected += (isset($obj->rejected)) ? $obj->rejected : 0;
                $shares += (isset($obj->shares)) ? $obj->shares : 0;
            }

            $timestamp = round(($timestamp / $i), 0);
            $netweight = round(($netweight / $i), 0);
            $weight = round(($weight / $i), 0);
            $frequency = round(($frequency / $i), 0);
            $accepted = round(($accepted / $i), 0);
            $errors = round(($errors / $i), 0);
            $rejected = round(($rejected / $i), 0);
            $shares = round(($shares / $i), 0);
        }

        $o = false;
        if ($timestamp) {
            $o = array(
                "timestamp" => $timestamp,
                "seconds" => $seconds,
                "net_weight" => $netweight,
                "weight" => $weight,
                "expectedtime" => (isset($obj->expectedtime)) ? $obj->expectedtime : 0,
                "frequency" => $frequency,
                "accepted" => $accepted,
                "errors" => $errors,
                "rejected" => $rejected,
                "shares" => $shares
            );
        }

        return json_encode($o);
    }

    // Calculate and store the average statistics 5m / 1h / 1d
    public function storeAvgStats($period = 300, $time = false) {
        $now = ($time) ? $time : time();

        // Period is in seconds 5m:300 / 1h:3600 / 1d:86400
        $startTime = ($now - $period);
        $stats = $this->avgStats($period, $startTime, false);

        // Store average stats for period
        log_message("error", "Stored AVG stats for period " . $period . ": " . $stats);

        $this->redis->command("ZREM piratecashd_avg_stats_" . $period . " false");

        if ($stats)
            $this->redis->command("ZADD piratecashd_avg_stats_" . $period . " " . $now . " " . $stats);
    }

    // Calculate and store the average statistics 5m / 1h / 1d for old delta stats
    public function storeOldAvgStats($period) {
        $last = $this->redis->command("ZRANGE piratecashd_delta_stats 0 0");
        $last = json_decode($last[0]);
        $t = false;
        if (isset($last->timestamp))
            $t = $last->timestamp;

        if ($t) {
            for ($i = time(); $i >= $t; $i -= $period) {
                $this->storeAvgStats($period, $i);
            }
        }
    }

    // Store the live stats on Redis
    public function storeStats() {
        log_message('error', "Storing getsock...");

        $data = new stdClass();
        $stats = $this->getMinerStats();

        if ($stats) {
            $data = json_decode($this->getParsedStats($stats));

            $ph = (isset($stats['staking']['netstakeweight'])) ? round($stats['staking']['netstakeweight'] / 100000000, 0) : 0;
            $dh = (isset($stats['staking']['weight'])) ? round($stats['staking']['weight'] / 100000000, 0) : 0;
            $et = (isset($stats['staking']['expectedtime'])) ? $stats['staking']['expectedtime'] : 0;
            $fr = 0;
            $ac = 0;
            $hw = 0;
            $re = 0;
            $sh = 0;
            $ls = 0;

            // Get totals
            $o = array(
                "timestamp" => time(),
                "weight" => $dh,
                "net_weight" => $ph,
                "expectedtime" => $et,
                "avg_freq" => $fr,
                "accepted" => $ac,
                "errors" => $hw,
                "rejected" => $re,
                "shares" => $sh,
                "last_share" => $ls,
            );

            // Get latest
            $latest = $this->redis->command("ZREVRANGE piratecashd_totals_stats 0 0");
            $lf = 0;
            $la = 0;
            $le = 0;
            $lr = 0;
            $ls = 0;
            if ($latest) {
                $latest = json_decode($latest[0]);
                $lfr = $latest->avg_freq;
                $lac = $latest->accepted;
                $lhw = $latest->errors;
                $lre = $latest->rejected;
                $lsh = $latest->shares;
                $lls = (isset($latest->last_share)) ? $latest->last_share : 0;
            }

            // Get delta current-latest
            $delta = array(
                "timestamp" => time(),
                "weight" => $dh,
                "net_weight" => $ph,
                "expectedtime" => $et,
                "avg_freq" => max((int) ($fr - $lfr), 0),
                "accepted" => max((int) ($ac - $lac), 0),
                "errors" => max((int) ($hw - $lhw), 0),
                "rejected" => max((int) ($re - $lre), 0),
                "shares" => max((int) ($sh - $lsh), 0),
                "last_share" => $lls,
            );

            // Store delta
            $this->redis->command("ZADD piratecashd_delta_stats " . time() . " " . json_encode($delta));

            log_message('error', "Delta Stats stored as: " . json_encode($delta));

            // Store totals
            $this->redis->command("ZADD piratecashd_totals_stats " . time() . " " . json_encode($o));

            log_message('error', "Total Stats stored as: " . json_encode($o));
        }

        return $data;
    }

    function setCommandline($string) {
        return $this->redis->set("minerd_settings", $string);
    }

    function getCommandline() {
        return $this->redis->get("minerd_settings");
    }

    /*
      //
      // Clone/export/save/share configs
      //
     */

    public function importFile($post) {
        $config['upload_path'] = '/tmp/';
        $config['allowed_types'] = 'json|txt';
        $config['overwrite'] = true;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('import_system_config')) {
            $data = array('error' => $this->upload->display_errors());
        } else {
            $data = $this->upload->data();
            if (file_exists($data['full_path'])) {
                $json = file_get_contents($data['full_path']);
                if ($this->isJson($json)) {
                    $data = json_decode($json);
                    $this->redis->set("import_data_tmp", $json);
                } else {
                    $data = array('error' => "File is not JSON valid");
                }
            }
        }

        return $data;
    }

    public function cloneSystem() {
        $data = $this->redis->get("import_data_tmp");
        if ($this->isJson($data)) {
            foreach (json_decode($data) as $key => $value) {
                $this->redis->set($key, $value);
            }

            $this->session->set_flashdata('message', '<b>Success!</b> System cloned!');
            $this->session->set_flashdata('message_type', 'success');
        }

        log_message("error", "Cloning the system with this data: " . $data);

        $this->redis->del("import_data_tmp");


        return true;
    }

    function deleteSavedConfig($id) {
        return $this->redis->command("HDEL saved_miner_configs " . $id);
    }

    function loadSavedConfig($id) {
        $encoded = $this->redis->command("HGET saved_miner_configs " . $id);

        if ($encoded) {
            $obj = json_decode(base64_decode($encoded));

            if (is_object($obj)) {
                $settings = $obj->settings;
                $this->redis->set("manual_options", 1);
                $this->redis->set("guided_options", false);
                $this->redis->set("minerd_manual_settings", $settings);
                $settings .= " -c ";
                $this->setCommandline($settings);

                // Startup script rc.local
                $this->saveStartupScript($obj->software);

                $this->session->set_flashdata('message', '<b>Success!</b> Miner config loaded!');
                $this->session->set_flashdata('message_type', 'success');
            }
        }
    }

    function shareSavedConfig($post) {
        $encoded = $this->redis->command("HGET saved_miner_configs " . $post['config_id']);

        $data = array("error" => true);

        if ($encoded) {
            $obj = json_decode(base64_decode($encoded));

            if (is_object($obj)) {
                $data = array('timestamp' => $obj->timestamp, 'description' => $post['config_description'], 'miner' => $obj->software, 'settings' => $obj->settings);

                $result = $this->useCurl($this->config->item('minera_api_url') . '/sendMinerConfig', false, "POST", json_encode($data));

                log_message("error", "Config sent to Minera: " . json_encode($data));

                $this->session->set_flashdata('message', '<b>Success!</b> Thanks to share your config');
                $this->session->set_flashdata('message_type', 'success');
            }
        }

        return $data;
    }

    /*
      //
      // Crypto rates related stuff
      //
     */

    // Get profitability per coin
    public function getProfitability($coin = null) {
        $ctx = stream_context_create(array('http' => array('timeout' => 10)));
        $profit = json_encode(array());

        $profit = @file_get_contents($this->config->item('minera_api_url') . '/profit', 0, $ctx);

        return $profit;
    }

    public function getAvgProfitability() {
        if (version_compare(PHP_VERSION, '5.4.0', '>=') && !(defined('JSON_C_VERSION') && PHP_INT_SIZE > 4)) {
            $profits = json_decode($this->redis->get('coins_profitability'), true, 512, JSON_BIGINT_AS_STRING);
        } else {
            $profits = json_decode($this->redis->get('coins_profitability'), true, 512);
        }

        $i = 1;
        $sum = 0;
        $ltc = 0;

        if (count($profits) > 0) {
            foreach ($profits as $k => $v) {
                if (isset($v->btc_profitability) && $v->symbol === "ltc")
                    $ltc = $v->btc_profitability;
            }

            foreach ($profits as $profit) {
                if (isset($v->btc_profitability) && isset($profit->symbol) && $profit->symbol !== "btc" && $profit->symbol !== "ltc" && $profit->btc_profitability >= $ltc) {
                    $sum += $profit->btc_profitability;
                    $i++;
                }
            }
        }

        $o = (($sum + $ltc) / $i);

        return ($o > 0) ? number_format($o, 8) : false;
    }

    // Get Bitstamp API to look at BTC/USD rates
    public function getBtcUsdRates() {
        // wait 1d before recheck
        if (time() > ($this->redis->get("bitstamp_update") + 600)) {
            log_message('error', "Refreshing Bitstamp data");

            $object = false;

            if ($json = @file_get_contents("https://www.bitstamp.net/api/ticker/")) {
                if ($jsonConv = @file_get_contents('https://www.bitstamp.net/api/eur_usd/')) {
                    $conv = json_decode($jsonConv);
                    $exchangeEur = ($conv->sell + $conv->buy) / 2;
                    $a = json_decode($json);
                    $o = array(
                        "high_eur" => round(($a->high / $exchangeEur), 2),
                        "last_eur" => round(($a->last / $exchangeEur), 2),
                        "high" => $a->high,
                        "last" => $a->last,
                        "timestamp" => $a->timestamp,
                        "bid_eur" => round(($a->bid / $exchangeEur), 2),
                        "vwap_eur" => round(($a->vwap / $exchangeEur), 2),
                        "bid" => $a->bid,
                        "vwap" => $a->vwap,
                        "volume" => $a->volume,
                        "low_eur" => round(($a->low / $exchangeEur), 2),
                        "ask_eur" => round(($a->ask / $exchangeEur), 2),
                        "low" => $a->low,
                        "ask" => $a->ask,
                        "eur_usd" => $exchangeEur
                    );

                    $object = json_decode(json_encode($o), FALSE);
                }
            }

            if ($object) {
                $this->redis->set("bitstamp_update", time());

                $this->redis->set("bitstamp_data", json_encode($object));

                return $object;
            }
        } else {
            return json_decode($this->redis->get("bitstamp_data"));
        }
    }

    // Refresh data IDs/Values
    public function updateAltcoinsRates() {

        log_message('error', "Refreshing Altcoins rates data");

        return json_decode(array("error" => "true"));
    }


    // Check if the minerd if running
    public function isOnline() {

        $ip = "127.0.0.1";
        $port = 11888;

        if (!($fp = @fsockopen($ip, $port, $errno, $errstr, 1)))
            return false;

        if (is_resource($fp))
            fclose($fp);

        return true;
    }

    // Check RPi temp
    public function checkTemp() {
        if (file_exists($this->config->item("rpi_temp_file"))) {
            $scale = ($this->redis->get("dashboard_temp")) ? $this->redis->get("dashboard_temp") : "c";
            $temp = number_format(( (int) exec("cat " . $this->config->item("rpi_temp_file")) / 1000), 2);

            if ($scale == "f")
                $temp = intval((9 / 5) * $temp + 32);

            return array("value" => $temp, "scale" => $scale);
        }
        else {
            return false;
        }
    }

    public function checkMinerIsUp() {
        // Check if miner is not manually stopped
        if ($this->redis->get("minerd_status")) {
            if ($this->isOnline() === false) {
                log_message('error', "It seems wallet is down, trying to restart it");
                // Force stop and killall
                $this->walletStop();
                // Restart miner
                $this->walletStart();
            }

            log_message('error', "Wallet is up");
        }

        return;
    }

    public function readCustomMinerDir() {
        $files = array();
        $newActiveCustomMiners = array();

        $activeCustomMiners = (json_decode($this->redis->get('active_custom_miners'))) ? json_decode($this->redis->get('active_custom_miners')) : array();

        if ($handle = opendir(FCPATH . 'minera-bin/custom/')) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != ".." && $entry != "README.custom") {
                    $files[] = $entry;
                }
            }

            closedir($handle);

            foreach ($activeCustomMiners as $activeCustomMiner) {
                // Remove active ones from redis if someone has removed the file by hand
                if (in_array($activeCustomMiner, $files)) {
                    $newActiveCustomMiners[] = $activeCustomMiner;
                }
            }

            $this->redis->set('active_custom_miners', json_encode($newActiveCustomMiners));
        }
        return $files;
    }

    // Refresh miner confs
    public function refreshMinerConf() {
        // wait 1w before recheck
        if (file_exists(FCPATH . "miners_conf.json") && time() > ($this->redis->get("miners_conf_update") + 86400 * 7)) {
            log_message('error', "Refreshing Miners conf data");

            $data = json_decode(file_get_contents(FCPATH . "miners_conf.json"), true);

            if ($data) {
                $this->redis->set("miners_conf_update", time());

                $this->redis->set("miners_conf", json_encode($data));
            }
        }

        return $this->redis->get("miners_conf");
    }

    public function is_valid_domain_name($domain_name) {
        return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name) //valid chars check
                && preg_match("/^.{1,253}$/", $domain_name) //overall length check
                && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name) ); //length of each label
    }

    public function setSystemHostname($hostname) {
        if ($this->is_valid_domain_name($hostname)) {
            exec("sudo hostname " . $hostname);
            exec("echo " . $hostname . " | sudo tee /etc/hostname");
            exec("echo 127.0.0.1     " . $hostname . " | sudo tee --append /etc/hosts");
            return true;
        } else {
            return false;
        }
    }

    public function setSystemUserPassword($password) {
        exec("echo 'minera:" . $password . "' | sudo -S /usr/sbin/chpasswd");
        return true;
    }

    // Call shutdown cmd
    public function shutdown() {
        log_message('error', "Shutdown cmd called");

        $this->walletStop();
        $this->redis->del("cron_lock");
        $this->redis->command("BGSAVE");
        sleep(2);

        exec("sudo shutdown -h now");

        return true;
    }

    // Call reboot cmd
    public function reboot() {
        log_message('error', "Reboot cmd called");

        $this->walletStop();
        $this->redis->del("cron_lock");
        $this->redis->command("BGSAVE");
        sleep(2);

        exec("sudo reboot");

        return true;
    }

    // Write rc.local startup file
    public function saveStartupScript($minerSoftware, $delay = 5, $extracommands = false) {
        $command = array(" /usr/local/bin/piratecashd");

        $rcLocal = file_get_contents(FCPATH . "rc.local.minera");

        $rcLocal .= "\nsu - pirate -c \"" . implode(' ', $command) . "\"\n$extracommands\nexit 0";

        file_put_contents('/etc/rc.local', $rcLocal);

        log_message('error', "Startup script saved: " . var_export($rcLocal, true));

        return true;
    }

    public function tailFile($filename, $lines) {
        $file = file($filename);

        if (count($file) > 0) {
            for ($i = count($file) - $lines; $i < count($file); $i++) {
                if ($i >= 0 && $file[$i]) {
                    $readlines[] = $file[$i] . "\n";
                }
            }
        } else {
            $readlines = array('No logs found');
        }

        return $readlines;
    }

    public function getBlocks() {
        return '{"blocks":"' . $this->rpc->getinfo()['blocks'] . '"}';
    }

    public function getStakingDashboard() {
        try {
            $getinfo = $this->rpc->getinfo();
            $getstakinginfo = $this->rpc->getstakinginfo();
            $unit = 'minutes';
            $interval = $getstakinginfo['expectedtime'] / 60;
            $hours = $interval / 60;
            $days = $hours / 24;
            if ($hours > 1) {
                $interval = $hours;
                $unit = "hours";
            }
            if ($days > 1) {
                $interval = $days;
                $unit = "days";
            }
            $getblock = $this->rpc->getblockbynumber($getinfo['blocks']);
            return '{"error": 0, "blocks": ' . $getinfo['blocks']
                    . ', "expected_time": "' . sprintf('%.2f', $interval) . ' ' . $unit
                    . '", "available": ' . $getinfo['balance']
                    . ', "stake": ' . $getinfo['stake']
                    . ', "version": "' . $getinfo['version']
                    . '", "connections": ' . $getinfo['connections']
                    . ', "enabled": ' . ($getstakinginfo['enabled'] ? 1 : 0)
                    . ', "staking": ' . ($getstakinginfo['staking'] ? 1 : 0)
                    . ', "last_block_time": ' . $getblock['time']
                    . ', "raspinode": "' . $this->util_model->currentVersion(true) . '"}';
        } catch (Exception $ex) {
            return '{"error": 1}';
        }
    }

    // Stop wallet
    public function walletStop() {
        exec("sudo -u pirate /usr/local/bin/piratecashd stop");
        sleep(9);
        exec("sudo -u pirate /usr/bin/killall -s9 piratecashd");

        $this->redis->set("minerd_status", false);

        log_message('error', $this->_minerdSoftware . " stopped");

        $this->redis->command("BGSAVE");

        return true;
    }

    // Start wallet
    public function walletStart() {
        $this->resetCounters();
        $this->checkCronIsRunning();

        $this->redis->set("minerd_status", true);

        $finalCommand = "sudo -u pirate /usr/local/bin/piratecashd";

        exec($finalCommand, $out);

        log_message('error', "Minerd started with command: $finalCommand - Output was: " . var_export($out, true));

        sleep(9);

        $this->redis->command("BGSAVE");

        return true;
    }

    // Restart wallet
    public function walletRestart() {
        $this->resetCounters();

        $this->walletStop();
        sleep(1);

        $this->walletStart();
        sleep(1);

        return true;
    }

    // Save a fake last data to get correct next delta
    public function resetCounters() {
        $reset = array(
            "timestamp" => time(),
            "pool_hashrate" => 0,
            "hashrate" => 0,
            "avg_freq" => 0,
            "accepted" => 0,
            "errors" => 0,
            "rejected" => 0,
            "shares" => 0
        );

        // Reset the counters
        $this->redis->command("ZADD piratecashd_totals_stats " . time() . " " . json_encode($reset));
    }

    // Check if cron is running and force the deletion of lock if not.
    public function checkCronIsRunning() {
        $o = shell_exec("ps aux |grep 'index.php app cron'");

        if (preg_match("/\/bin\/sh -c php/", $o)) {
            log_message("error", "Cron running...");
            return true;
        } else {
            log_message("error", "Cron NOT running. Deleting lock.");
            $this->redis->del("cron_lock");
            return false;
        }
    }

    // Call update cmd
    public function update() {
        $this->session->unset_userdata("loggedin");
        $this->walletStop();
        $this->resetCounters();
        sleep(3);

        $lines = array();
        // Pull the latest code from github
        exec("cd " . FCPATH . " && sudo -u pirate sudo git fetch --all && sudo git reset --hard origin/master", $out);

        $logmsg = "Update request from " . $this->currentVersion() . " to " . $this->redis->command("HGET raspinode_update new_version") . " : " . var_export($out, true);

        $lines[] = $logmsg;

        log_message('error', $logmsg);

        $this->redis->del("altcoins_update");
        $this->util_model->updateAltcoinsRates();
        $this->redis->del("raspinode_update");
        $this->redis->del("raspinode_version");
        $this->checkUpdate();

        // Run upgrade script
        exec("cd " . FCPATH . " && sudo -u pirate sudo ./upgrade_raspinode.sh", $out);

        $logmsg = "Running upgrade script" . var_export($out, true);

        $lines[] = $logmsg;

        log_message('error', $logmsg);

        $logmsg = "End Update";
        $lines[] = $logmsg;
        log_message('error', $logmsg);

        sleep(5);
        $this->walletStart();

        return json_encode($lines);
    }

    // Reset Minera data
    public function reset($action) {
        switch ($action) {
            case "charts":
                $this->redis->del("piratecashd_totals_stats");
                $this->redis->del("piratecashd_delta_stats");
                $this->redis->del("minerd_stats");
                $this->redis->del("piratecashd_avg_stats_86400");
                $this->redis->del("piratecashd_avg_stats_3600");
                $this->redis->del("piratecashd_avg_stats_300");
                $o = json_encode(array("success" => true));
                break;
            case "options":
                $this->redis->set("guided_options", 1);
                $this->redis->set("manual_options", 0);
                $o = json_encode(array("success" => true));
                break;
            case "logs":
                array_map('unlink', glob("application/logs/*"));
                $o = json_encode(array("success" => true));
                break;
            default:
                $o = json_encode(array("err" => true));
        }

        return $o;
    }

    public function factoryReset() {
        $this->walletStop();
        sleep(3);

        // SET
        $this->redis->set("minerd_autorestart", 0);
        $this->redis->set("minerd_delaytime", 5);
        $this->redis->set("scheduled_event_time", "");
        $this->redis->set("minerd_autorestart_time", 600);
        $this->redis->set("minerd_manual_settings", "");
        $this->redis->set("minerd_extraoptions", "");
        $this->redis->set("minerd_settings", "");
        $this->redis->set("minerd_autotune", 0);
        $this->redis->set("mobileminer_system_name", "");
        $this->redis->set("mobileminer_email", "");
        $this->redis->set("mobileminer_appkey", "");
        $this->redis->set("minerd_startfreq", 0);
        $this->redis->set("current_frequencies", 0);
        $this->redis->set("dashboard_temp", "c");
        $this->redis->set("dashboard_table_records", 10);
        $this->redis->set("minera_timezone", "GMT");
        $this->redis->set("dashboard_devicetree", 0);
        $this->redis->set("manual_options", 0);
        $this->redis->set("minerd_autorecover", 0);
        $this->redis->set("scheduled_event_action", "");
        $this->redis->set("dashboard_skin", "black");
        $this->redis->set("guided_options", 1);
        $this->redis->set("mobileminer_enabled", 0);
        $this->redis->set("minerd_autorestart_devices", 0);
        $this->redis->set("minerd_log", 0);
        $this->redis->set("minerd_status", 0);
        $this->redis->set("minerd_scrypt", 0);
        $this->redis->set("dashboard_refresh_time", 300);
        $this->redis->set("scheduled_event_start_time", "");
        $this->redis->set("raspinode_password", "e0a0bad34f2ac763f60ff775560143e1");
        $this->redis->set("dashboard_coin_rates", json_encode(array()));
        $this->redis->set("system_extracommands", "");
        $this->redis->set("minerd_append_conf", 1);
        $this->redis->set("minerd_debug", 0);
        $this->redis->set("minerd_autodetect", 0);
        $this->redis->set("minerd_api_allow_extra", "");

        // DEL
        $this->redis->del("raspinode_version");
        $this->redis->del("active_custom_miners");
        $this->redis->del("raspinode_update");
        $this->redis->del("piratecashd_avg_stats_86400");
        $this->redis->del("piratecashd_avg_stats_3600");
        $this->redis->del("piratecashd_avg_stats_300");
        $this->redis->del("saved_miner_configs");
        $this->redis->del("bitstamp_data");
        $this->redis->del("raspinode_remote_config");
        $this->redis->del("export_settings");
        $this->redis->del("piratecashd_totals_stats");
        $this->redis->del("miners_conf");
        $this->redis->del("miners_conf_update");
        $this->redis->del("piratecashd_delta_stats");
        $this->redis->del("saved_miner_config:*");
        $this->redis->del("import_data_tmp");
        $this->redis->del("bitstamp_update");
        $this->redis->del("altcoins_update");

        return true;
    }

    // Check Minera version
    public function checkUpdate() {
        // wait 1h before recheck
        if (time() > ($this->redis->command("HGET raspinode_update timestamp") + 3600)) {
            log_message('error', "Checking Minera updates");

            $latestConfig = $this->getRemoteJsonConfig();
            $localVersion = $this->currentVersion();

            if (isset($latestConfig->version)) {
                $this->redis->command("HSET raspinode_update timestamp " . time());
                $this->redis->command("HSET raspinode_update new_version " . $latestConfig->version);

                if ($latestConfig->version != $localVersion) {
                    log_message('error', "Found a new Minera update");

                    $this->redis->command("HSET raspinode_update value 1");
                    return true;
                }

                $this->redis->command("HSET raspinode_update value 0");
            }
        } else {
            if ($this->redis->command("HGET raspinode_update value"))
                return true;
            else
                return false;
        }
    }

    // Get local Minera version
    public function currentVersion($cron = false) {
        // wait 1h before recheck
        if (time() > ((int) $this->redis->command("HGET raspinode_version timestamp") + 3600) && $cron == false) {
            $this->redis->command("HSET raspinode_version timestamp " . time());
            $localConfig = json_decode(file_get_contents(base_url('raspinode.json')));
            $this->redis->command("HSET raspinode_version value " . $localConfig->version);
            return $localConfig->version;
        } else {
            return $this->redis->command("HGET raspinode_version value");
        }
    }

    // Set the dashboard box status
    public function setBoxStatus($boxId, $status) {
        if ($boxId) {
            $boxStatuses = json_decode($this->redis->get('box_status'), true);

            if (is_array($boxStatuses)) {
                $boxStatuses[$boxId] = $status;
            } else {
                $boxStatuses = array();
                $boxStatuses[$boxId] = $status;
            }

            $this->redis->set('box_status', json_encode($boxStatuses));

            return array("success" => true, $boxId => $status);
        }
    }

    public function getMacLinux() {
        exec('cat /sys/class/net/eth0/address', $result);

        if (!isset($result[0]))
            return false;

        return $result[0];
    }

    // Get Remote configuration from Github
    public function getRemoteJsonConfig() {
        $remoteConfig = @file_get_contents($this->config->item('remote_config_url'));

        $this->redis->set("raspinode_remote_config", $remoteConfig);

        return json_decode($remoteConfig);
    }

    // Return saved remote configuration from local Redis
    public function returnRemoteJsonConfig() {
        return json_decode($this->redis->get("raspinode_remote_config"));
    }

    /*
      // Check Mobileminer is enabled
     */

    public function isEnableMobileminer() {
        if ($this->redis->get("mobileminer_enabled")) {
            if ($this->redis->get("mobileminer_system_name") && $this->redis->get("mobileminer_email") && $this->redis->get("mobileminer_appkey")) {
                return true;
            }
        }

        return false;
    }


    public function getRandomStarName() {
        $array = array("Andromeda", "Antlia", "Apus", "Aquarius", "Aquila", "Ara", "Aries", "Auriga", "Caelum", "Camelopardalis", "Cancer", "Canes Venatici", "Canis Major", "Canis Minor", "Capricornus", "Carina", "Cassiopeia", "Centaurus", "Cepheus", "Cetus", "Chamaeleon", "Circinus", "Columba", "Coma Berenices", "Corona Austrina", "Corona Borealis", "Corvus", "Crater", "Crux", "Cygnus", "Delphinus", "Dorado", "Draco", "Equuleus", "Eridanus", "Fornax", "Gemini", "Grus", "Hercules", "Horologium", "Hydra", "Hydrus", "Indus", "Lacerta", "Leo", "Leo Minor", "Lepus", "Libra", "Lupus", "Lynx", "Lyra", "Mensa", "Microscopium", "Monoceros", "Musca", "Norma", "Octans", "Ophiuchus", "Orion", "Pavo", "Pegasus", "Perseus", "Phoenix", "Pictor", "Pisces", "Piscis Austrinus", "Puppis", "Pyxis", "Reticulum", "Sagitta", "Sagittarius", "Scorpius", "Sculptor", "Scutum", "Serpens", "Sextans", "Taurus", "Telescopium", "Triangulum", "Triangulum Australe", "Tucana", "Ursa Major", "Ursa Minor", "Vela", "Virgo", "Volans", "Vulpecula");

        return $array[array_rand($array)];
    }

    public function setTimezone($timezone) {
        exec("echo '" . $timezone . "' | sudo tee /etc/timezone && sudo dpkg-reconfigure -f noninteractive tzdata");
        $this->redis->set("minera_timezone", $timezone);
    }

    public function convertHashrate($hash) {
        if ($hash > 900000000)
            return round($hash / 1000000000, 2) . 'Gh/s';
        elseif ($hash > 900000)
            return round($hash / 1000000, 2) . 'Mh/s';
        elseif ($hash > 900)
            return round($hash / 1000, 2) . 'Kh/s';
        else
            return $hash;
    }

    // Check Internet connection
    public function checkConn() {
        if (!$fp = fsockopen("www.google.com", 80)) {
            return false;
        }

        if (is_resource($fp))
            fclose($fp);

        return true;
    }

    public function getSysUptime() {
        return strtok(exec("cat /proc/uptime"), ".");
    }

    public function isJson($string) {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    // Socket server to get a fake miner to do tests
    public function fakeMiner() {
        $server = stream_socket_server("tcp://127.0.0.1:1337", $errno, $errorMessage);

        if ($server === false) {
            throw new UnexpectedValueException("Could not bind to socket: $errorMessage");
        }

        for (;;) {
            $client = @stream_socket_accept($server);

            if ($client) {
                // Inject stdin
                //stream_copy_to_stream($client, $client);
                // Inject some json
                $j = file_get_contents('test.json');
                fwrite($client, $j);

                fclose($client);
            }
        }
    }

    function get_furl($url) {
        $furl = false;

        // First check response headers
        $headers = get_headers($url);

        // Test for 301 or 302
        if (preg_match('/^HTTP\/\d\.\d\s+(301|302)/', $headers[0])) {
            foreach ($headers as $value) {
                if (substr(strtolower($value), 0, 9) == "location:") {
                    $furl = trim(substr($value, 9, strlen($value)));
                }
            }
        }
        // Set final URL
        $furl = ($furl) ? $furl : $url;

        return $furl;
    }

    public function useCurl($url, $params, $method, $post = false) {
        if ($params)
            $ch = curl_init($this->get_furl($url) . "?" . http_build_query($params));
        else
            $ch = curl_init($this->get_furl($url));

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        if ($post) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($post))
            );
        }
        if ($method == "DELETE") {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        }

        $result = curl_exec($ch);

        if (!curl_errno($ch)) {
            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            // Not used
        }

        curl_close($ch);

        return $result;
    }

}
