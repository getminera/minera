<?php

if (!defined('BASEPATH'))
    die();

class App extends CI_Controller {

    public function __construct() {
        parent::__construct();

        // Set the general timezone
        $timezone = ($this->redis->get("minera_timezone")) ? $this->redis->get("minera_timezone") : 'Europe/Kiev';
        date_default_timezone_set($timezone);
    }

    /*
      // Index/lock screen controller
     */

    public function index() {
        // Always try to assign the mineraId if not present
        $mineraSystemId = $this->util_model->generateMineraId();
        $this->redis->del("minera_update");
        $this->util_model->checkUpdate();

        // Remove old Minera pool
        $this->util_model->removeOldMineraPool();

        if (!$this->redis->command("EXISTS dashboard_devicetree"))
            $this->redis->set("dashboard_devicetree", 1);
        if (!$this->redis->command("EXISTS dashboard_box_profit"))
            $this->redis->set("dashboard_box_profit", 1);
        if (!$this->redis->command("EXISTS dashboard_box_local_miner"))
            $this->redis->set("dashboard_box_local_miner", 1);
        if (!$this->redis->command("EXISTS dashboard_box_local_pools"))
            $this->redis->set("dashboard_box_local_pools", 1);
        if (!$this->redis->command("EXISTS dashboard_box_network_details"))
            $this->redis->set("dashboard_box_network_details", 1);
        if (!$this->redis->command("EXISTS dashboard_box_network_pools_details"))
            $this->redis->set("dashboard_box_network_pools_details", 1);
        if (!$this->redis->command("EXISTS dashboard_box_chart_shares"))
            $this->redis->set("dashboard_box_chart_shares", 1);
        if (!$this->redis->command("EXISTS dashboard_box_chart_system_load"))
            $this->redis->set("dashboard_box_chart_system_load", 1);
        if (!$this->redis->command("EXISTS dashboard_box_chart_hashrates"))
            $this->redis->set("dashboard_box_chart_hashrates", 1);
        if (!$this->redis->command("EXISTS dashboard_box_scrypt_earnings"))
            $this->redis->set("dashboard_box_scrypt_earnings", 1);
        if (!$this->redis->command("EXISTS dashboard_box_log"))
            $this->redis->set("dashboard_box_log", 1);

        $data['now'] = time();
        $data['minera_system_id'] = $mineraSystemId;
        $data['raspinode_version'] = $this->util_model->currentVersion(true);
        $data['browserMining'] = $this->redis->get('browser_mining');
        $data['browserMiningThreads'] = $this->redis->get('browser_mining_threads');
        $data['env'] = $this->config->item('ENV');
        $data['sectionPage'] = 'lockscreen';
        $data['htmlTag'] = "lockscreen";
        $data['pageTitle'] = "Welcome to Minera";
        $data['isOnline'] = $this->util_model->isOnline();

        $this->load->view('include/header', $data);
        $this->load->view('lockscreen');
        $this->load->view('include/footer', $data);
    }

    /*
      // Login controller
     */

    public function login() {
        $storedp = $this->redis->get('raspinode_password');
        if (preg_match('/^[0-9a-f]{40}$/', $storedp)) {
            $storedp = $storedp;
        } elseif (preg_match('/^[a-f0-9]{32}$/', $storedp)) {
            if ($this->input->post('password', true) && md5($this->input->post('password')) == $storedp) {
                $storedp = sha1($this->input->post('password', true));
                $this->redis->set('raspinode_password', $storedp);
            }
        } else {
            $storedp = sha1($this->redis->get('raspinode_password'));
            $this->redis->set('raspinode_password', $storedp);
        }

        if ($this->input->post('password', true) && sha1($this->input->post('password')) == $storedp) {
            $this->session->set_userdata("loggedin", $storedp);
            redirect('app/dashboard');
        } else
            redirect('app/index');
    }

    /*
      // Dashboard controller
     */

    public function wallet() {
        $this->util_model->isLoggedIn();

        //var_export($this->redis->command("HGETALL box_status"));
        $boxStatuses = json_decode($this->redis->get("box_status"), true);

        $data['boxStatuses'] = array();
        if (count($boxStatuses > 0)) {
            $data['boxStatuses'] = $boxStatuses;
        }

        $data['now'] = time();
        $data['sectionPage'] = 'dashboard';
        $data['minerdPools'] = json_decode($this->util_model->getPools());
        $data['isOnline'] = $this->util_model->isOnline();
        $data['minerdLog'] = $this->redis->get('minerd_log');
        $data['savedFrequencies'] = $this->redis->get('current_frequencies');
        $data['htmlTag'] = "dashboard";
        $data['appScript'] = true;
        $data['settingsScript'] = false;
        $data['mineraUpdate'] = $this->util_model->checkUpdate();
        $data['dashboard_refresh_time'] = $this->redis->get("dashboard_refresh_time");
        $data['dashboardTableRecords'] = $this->redis->get("dashboard_table_records");
        $data['dashboardDevicetree'] = ($this->redis->get("dashboard_devicetree")) ? $this->redis->get("dashboard_devicetree") : false;
        $data['dashboardBoxProfit'] = ($this->redis->get("dashboard_box_profit")) ? $this->redis->get("dashboard_box_profit") : false;
        $data['dashboardBoxLocalMiner'] = ($this->redis->get("dashboard_box_local_miner")) ? $this->redis->get("dashboard_box_local_miner") : false;
        $data['dashboardBoxLocalPools'] = ($this->redis->get("dashboard_box_local_pools")) ? $this->redis->get("dashboard_box_local_pools") : false;
        $data['dashboardBoxNetworkDetails'] = ($this->redis->get("dashboard_box_network_details")) ? $this->redis->get("dashboard_box_network_details") : false;
        $data['dashboardBoxNetworkPoolsDetails'] = ($this->redis->get("dashboard_box_network_pools_details")) ? $this->redis->get("dashboard_box_network_pools_details") : false;
        $data['dashboardBoxChartShares'] = ($this->redis->get("dashboard_box_chart_shares")) ? $this->redis->get("dashboard_box_chart_shares") : false;
        $data['dashboardBoxChartSystemLoad'] = ($this->redis->get("dashboard_box_chart_system_load")) ? $this->redis->get("dashboard_box_chart_system_load") : false;
        $data['dashboardBoxChartHashrates'] = ($this->redis->get("dashboard_box_chart_hashrates")) ? $this->redis->get("dashboard_box_chart_hashrates") : false;
        $data['dashboardBoxScryptEarnings'] = ($this->redis->get("dashboard_box_scrypt_earnings")) ? $this->redis->get("dashboard_box_scrypt_earnings") : false;
        $data['dashboardBoxLog'] = ($this->redis->get("dashboard_box_log")) ? $this->redis->get("dashboard_box_log") : false;
        $data['pageTitle'] = ($this->redis->get("mobileminer_system_name")) ? $this->redis->get("mobileminer_system_name") . " > Minera - Dashboard" : "Minera - Dashboard";
        $data['dashboardSkin'] = ($this->redis->get("dashboard_skin")) ? $this->redis->get("dashboard_skin") : "black";
        $data['localAlgo'] = $this->util_model->checkAlgo($this->util_model->isOnline());
        $data['browserMining'] = $this->redis->get('browser_mining');
        $data['browserMiningThreads'] = $this->redis->get('browser_mining_threads');
        $data['env'] = $this->config->item('ENV');
        $data['mineraSystemId'] = $this->redis->get("minera_system_id");

        $this->load->view('include/header', $data);
        $this->load->view('include/sidebar', $data);
        $this->load->view('wallet', $data);
        $this->load->view('include/footer', $data);
    }

    public function dashboard() {
        $this->util_model->isLoggedIn();

        //var_export($this->redis->command("HGETALL box_status"));
        $boxStatuses = json_decode($this->redis->get("box_status"), true);

        $data['boxStatuses'] = array();
        if (count($boxStatuses > 0)) {
            $data['boxStatuses'] = $boxStatuses;
        }

        $data['now'] = time();
        $data['sectionPage'] = 'dashboard';
        $data['minerdPools'] = json_decode($this->util_model->getPools());
        $data['isOnline'] = $this->util_model->isOnline();
        $data['minerdLog'] = $this->redis->get('minerd_log');
        $data['savedFrequencies'] = $this->redis->get('current_frequencies');
        $data['htmlTag'] = "dashboard";
        $data['appScript'] = true;
        $data['settingsScript'] = false;
        $data['mineraUpdate'] = $this->util_model->checkUpdate();
        $data['dashboard_refresh_time'] = $this->redis->get("dashboard_refresh_time");
        $data['dashboardTableRecords'] = $this->redis->get("dashboard_table_records");
        $data['dashboardDevicetree'] = ($this->redis->get("dashboard_devicetree")) ? $this->redis->get("dashboard_devicetree") : false;
        $data['dashboardBoxProfit'] = ($this->redis->get("dashboard_box_profit")) ? $this->redis->get("dashboard_box_profit") : false;
        $data['dashboardBoxLocalMiner'] = ($this->redis->get("dashboard_box_local_miner")) ? $this->redis->get("dashboard_box_local_miner") : false;
        $data['dashboardBoxLocalPools'] = ($this->redis->get("dashboard_box_local_pools")) ? $this->redis->get("dashboard_box_local_pools") : false;
        $data['dashboardBoxNetworkDetails'] = ($this->redis->get("dashboard_box_network_details")) ? $this->redis->get("dashboard_box_network_details") : false;
        $data['dashboardBoxNetworkPoolsDetails'] = ($this->redis->get("dashboard_box_network_pools_details")) ? $this->redis->get("dashboard_box_network_pools_details") : false;
        $data['dashboardBoxChartShares'] = ($this->redis->get("dashboard_box_chart_shares")) ? $this->redis->get("dashboard_box_chart_shares") : false;
        $data['dashboardBoxChartSystemLoad'] = ($this->redis->get("dashboard_box_chart_system_load")) ? $this->redis->get("dashboard_box_chart_system_load") : false;
        $data['dashboardBoxChartHashrates'] = ($this->redis->get("dashboard_box_chart_hashrates")) ? $this->redis->get("dashboard_box_chart_hashrates") : false;
        $data['dashboardBoxScryptEarnings'] = ($this->redis->get("dashboard_box_scrypt_earnings")) ? $this->redis->get("dashboard_box_scrypt_earnings") : false;
        $data['dashboardBoxLog'] = ($this->redis->get("dashboard_box_log")) ? $this->redis->get("dashboard_box_log") : false;
        $data['pageTitle'] = ($this->redis->get("mobileminer_system_name")) ? $this->redis->get("mobileminer_system_name") . " > Minera - Dashboard" : "Minera - Dashboard";
        $data['dashboardSkin'] = ($this->redis->get("dashboard_skin")) ? $this->redis->get("dashboard_skin") : "black";
        $data['localAlgo'] = $this->util_model->checkAlgo($this->util_model->isOnline());
        $data['browserMining'] = $this->redis->get('browser_mining');
        $data['browserMiningThreads'] = $this->redis->get('browser_mining_threads');
        $data['env'] = $this->config->item('ENV');
        $data['mineraSystemId'] = $this->redis->get("minera_system_id");

        $this->load->view('include/header', $data);
        $this->load->view('include/sidebar', $data);
        $this->load->view('frontpage', $data);
        $this->load->view('include/footer', $data);
    }

    /*
      // Charts controller
     */

    public function charts() {
        $this->util_model->isLoggedIn();

        $data['now'] = time();
        $data['sectionPage'] = 'charts';
        $data['isOnline'] = $this->util_model->isOnline();
        $data['htmlTag'] = "charts";
        $data['chartsScript'] = true;
        $data['appScript'] = false;
        $data['settingsScript'] = false;
        $data['mineraUpdate'] = $this->util_model->checkUpdate();
        $data['pageTitle'] = ($this->redis->get("mobileminer_system_name")) ? $this->redis->get("mobileminer_system_name") . " > Minera - Charts" : "Minera - Charts";
        $data['dashboard_refresh_time'] = $this->redis->get("dashboard_refresh_time");
        $data['dashboardTableRecords'] = $this->redis->get("dashboard_table_records");
        $data['minerdLog'] = $this->redis->get('minerd_log');
        $data['dashboardSkin'] = ($this->redis->get("dashboard_skin")) ? $this->redis->get("dashboard_skin") : "black";
        $data['dashboardDevicetree'] = ($this->redis->get("dashboard_devicetree")) ? $this->redis->get("dashboard_devicetree") : false;
        $data['browserMining'] = $this->redis->get('browser_mining');
        $data['browserMiningThreads'] = $this->redis->get('browser_mining_threads');
        $data['env'] = $this->config->item('ENV');
        $data['mineraSystemId'] = $this->redis->get("minera_system_id");

        $this->load->view('include/header', $data);
        $this->load->view('include/sidebar', $data);
        $this->load->view('charts', $data);
        $this->load->view('include/footer');
    }

    /*
      // Settings controller
     */

    public function settings() {
        $this->util_model->isLoggedIn();

        $data['now'] = time();
        $data['sectionPage'] = 'settings';
        $this->config->load('timezones');
        $data['timezones'] = $this->config->item("timezones");

        $data['message'] = false;
        $data['message_type'] = false;

        if ($this->input->post('save_password')) {
            $password = trim($this->input->post('password'));
            $password2 = trim($this->input->post('password2'));
            if (empty($password) && empty($password2)) {
                $data['message'] = "<b>Warning!</b> Password can't be empty";
                $data['message_type'] = "warning";
            } elseif ($password != $password2) {
                $data['message'] = "<b>Warning!</b> Password mismatch";
                $data['message_type'] = "warning";
            } else {
                $this->redis->set("raspinode_password", sha1($password));
                $data['message'] = '<b>Success!</b> Password saved!';
                $data['message_type'] = "success";
            }
        }

        // Load Coin Rates
        $data['btc'] = $this->util_model->getBtcUsdRates();

        // Check custom miners
        $data['customMiners'] = $this->util_model->readCustomMinerDir();
        $data['activeCustomMiners'] = json_decode($this->redis->get('active_custom_miners'));

        // Load miner settings
        $data['builtInMinersConf'] = json_decode($this->util_model->refreshMinerConf());
        $data['minerdAutorestart'] = $this->redis->get('minerd_autorestart');
        $data['minerdAutorestartDevices'] = $this->redis->get('minerd_autorestart_devices');
        $data['minerdAutorestartTime'] = $this->redis->get('minerd_autorestart_time');
        $data['minerdAutorecover'] = $this->redis->get('minerd_autorecover');
        $data['minerdScrypt'] = $this->redis->get('minerd_scrypt');
        $data['minerdAutodetect'] = $this->redis->get('minerd_autodetect');
        $data['minerdAutotune'] = $this->redis->get('minerd_autotune');
        $data['minerdStartfreq'] = $this->redis->get('minerd_startfreq');
        $data['minerdExtraoptions'] = $this->redis->get('minerd_extraoptions');
        $data['minerdLog'] = $this->redis->get('minerd_log');
        $data['minerdDebug'] = $this->redis->get('minerd_debug');
        $data['minerdAppendConf'] = $this->redis->get('minerd_append_conf');
        $data['minerdManualSettings'] = $this->redis->get('minerd_manual_settings');
        $data['minerdSettings'] = $this->util_model->getCommandline();
        $data['minerdJsonSettings'] = $this->redis->get("minerd_json_settings");
        $data['minerdPools'] = $this->util_model->getPools();
        $data['minerdGuidedOptions'] = $this->redis->get("guided_options");
        $data['minerdManualOptions'] = $this->redis->get("manual_options");
        $data['minerdDelaytime'] = $this->redis->get("minerd_delaytime");
        $data['minerApiAllowExtra'] = $this->redis->get("minerd_api_allow_extra");
        $data['globalPoolProxy'] = $this->redis->get("pool_global_proxy");
        $data['browserMining'] = $this->redis->get('browser_mining');
        $data['browserMiningThreads'] = $this->redis->get('browser_mining_threads');
        $data['env'] = $this->config->item('ENV');

        // Load Dashboard settings
        $data['mineraStoredDonations'] = $this->util_model->getStoredDonations();
        $data['dashboard_refresh_time'] = $this->redis->get("dashboard_refresh_time");
        $dashboard_coin_rates = $this->redis->get("dashboard_coin_rates");
        $data['dashboard_coin_rates'] = (is_array(json_decode($dashboard_coin_rates))) ? json_decode($dashboard_coin_rates) : array();
        $data['cryptsy_data'] = $this->redis->get("cryptsy_data");
        $data['dashboardTemp'] = ($this->redis->get("dashboard_temp")) ? $this->redis->get("dashboard_temp") : "c";
        $data['dashboardSkin'] = ($this->redis->get("dashboard_skin")) ? $this->redis->get("dashboard_skin") : "black";
        $data['dashboardDevicetree'] = ($this->redis->get("dashboard_devicetree")) ? $this->redis->get("dashboard_devicetree") : false;
        $data['dashboardBoxProfit'] = ($this->redis->get("dashboard_box_profit")) ? $this->redis->get("dashboard_box_profit") : false;
        $data['dashboardBoxLocalMiner'] = ($this->redis->get("dashboard_box_local_miner")) ? $this->redis->get("dashboard_box_local_miner") : false;
        $data['dashboardBoxLocalPools'] = ($this->redis->get("dashboard_box_local_pools")) ? $this->redis->get("dashboard_box_local_pools") : false;
        $data['dashboardBoxNetworkDetails'] = ($this->redis->get("dashboard_box_network_details")) ? $this->redis->get("dashboard_box_network_details") : false;
        $data['dashboardBoxNetworkPoolsDetails'] = ($this->redis->get("dashboard_box_network_pools_details")) ? $this->redis->get("dashboard_box_network_pools_details") : false;
        $data['dashboardBoxChartShares'] = ($this->redis->get("dashboard_box_chart_shares")) ? $this->redis->get("dashboard_box_chart_shares") : false;
        $data['dashboardBoxChartSystemLoad'] = ($this->redis->get("dashboard_box_chart_system_load")) ? $this->redis->get("dashboard_box_chart_system_load") : false;
        $data['dashboardBoxChartHashrates'] = ($this->redis->get("dashboard_box_chart_hashrates")) ? $this->redis->get("dashboard_box_chart_hashrates") : false;
        $data['dashboardBoxScryptEarnings'] = ($this->redis->get("dashboard_box_scrypt_earnings")) ? $this->redis->get("dashboard_box_scrypt_earnings") : false;
        $data['dashboardBoxLog'] = ($this->redis->get("dashboard_box_log")) ? $this->redis->get("dashboard_box_log") : false;

        $data['dashboardTableRecords'] = ($this->redis->get("dashboard_table_records")) ? $this->redis->get("dashboard_table_records") : 5;
        $data['algo'] = $this->util_model->checkAlgo(false);

        // Load System settings
        $data['mineraHostname'] = gethostname();
        $data['mineraTimezone'] = $this->redis->get("minera_timezone");
        $data['systemExtracommands'] = $this->redis->get("system_extracommands");
        $data['scheduledEventStartTime'] = $this->redis->get("scheduled_event_start_time");
        $data['scheduledEventTime'] = $this->redis->get("scheduled_event_time");
        $data['scheduledEventAction'] = $this->redis->get("scheduled_event_action");
        $data['mineraSystemId'] = $this->redis->get("minera_system_id");

        // Load Mobileminer
        $data['mobileminerEnabled'] = $this->redis->get("mobileminer_enabled");
        $data['mobileminerSystemName'] = $this->redis->get("mobileminer_system_name");
        $data['mobileminerEmail'] = $this->redis->get("mobileminer_email");
        $data['mobileminerAppkey'] = $this->redis->get("mobileminer_appkey");

        // Everything else
        $data['savedFrequencies'] = $this->redis->get('current_frequencies');
        $data['isOnline'] = $this->util_model->isOnline();
        $data['mineraUpdate'] = $this->util_model->checkUpdate();
        $data['htmlTag'] = "settings";
        $data['appScript'] = false;
        $data['settingsScript'] = true;
        $data['pageTitle'] = "RaspiNode - Settings";
        $data['donationProfitability'] = ($prof = $this->util_model->getAvgProfitability()) ? $prof : "0.00020";

        // Saved Configs
        $data['savedConfigs'] = $this->redis->command("HVALS saved_miner_configs");

        $this->load->view('include/header', $data);
        $this->load->view('include/sidebar', $data);
        $this->load->view('settings', $data);
        $this->load->view('include/footer');
    }

    /*
      // Save Settings controller
     */

    public function save_settings() {
        $this->util_model->isLoggedIn();

        $extramessages = false;
        $dataObj = new stdClass();
        $mineraSystemId = $this->util_model->generateMineraId();

        if ($this->input->post('save_settings')) {
            $minerSoftware = "";

            $dashSettings = $this->input->post('dashboard_refresh_time');

            $coinRates = $this->input->post('dashboard_coin_rates');
            $this->redis->set("altcoins_update", (time() - 3600));
            $dashboardTemp = $this->input->post('dashboard_temp');
            $dashboardSkin = $this->input->post('dashboard_skin');
            $dashboardTableRecords = $this->input->post('dashboard_table_records');
            $dashboardDevicetree = $this->input->post('dashboard_devicetree');
            $dashboardBoxProfit = $this->input->post('dashboard_box_profit');
            $dashboardBoxLocalMiner = $this->input->post('dashboard_box_local_miner');
            $dashboardBoxLocalPools = $this->input->post("dashboard_box_local_pools");
            $dashboardBoxNetworkDetails = $this->input->post("dashboard_box_network_details");
            $dashboardBoxNetworkPoolsDetails = $this->input->post("dashboard_box_network_pools_details");
            $dashboardBoxChartShares = $this->input->post("dashboard_box_chart_shares");
            $dashboardBoxChartSystemLoad = $this->input->post("dashboard_box_chart_system_load");
            $dashboardBoxChartHashrates = $this->input->post("dashboard_box_chart_hashrates");
            $dashboardBoxScryptEarnings = $this->input->post("dashboard_box_scrypt_earnings");
            $dashboardBoxLog = $this->input->post("dashboard_box_log");

            // Pools
            $poolUrls = $this->input->post('pool_url');
            $poolUsernames = $this->input->post('pool_username');
            $poolPasswords = $this->input->post('pool_password');
            $poolProxy = $this->input->post('pool_proxy');

            $pools = array();
            foreach ($poolUrls as $key => $poolUrl) {
                if ($poolUrl) {
                    if (isset($poolUsernames[$key]) && isset($poolPasswords[$key])) {
                        $pools[] = array("url" => $poolUrl, "username" => $poolUsernames[$key], "password" => $poolPasswords[$key], "proxy" => $poolProxy[$key]);
                    }
                }
            }

            // Network miners
            $netMinersNames = $this->input->post('net_miner_name');
            $netMinersIps = $this->input->post('net_miner_ip');
            $netMinersPorts = $this->input->post('net_miner_port');
            $netMinersAlgos = $this->input->post('net_miner_algo');
            $netMinersTypes = $this->input->post('net_miner_type');

            // Network miners pools
            $netGroupPoolActives = $this->input->post('net_pool_active');
            $netGroupPoolUrls = $this->input->post('net_pool_url');
            $netGroupPoolUsernames = $this->input->post('net_pool_username');
            $netGroupPoolPasswords = $this->input->post('net_pool_password');

            // Save Custom miners
            $dataObj->custom_miners = $this->input->post('active_custom_miners');
            $this->redis->set('active_custom_miners', json_encode($this->input->post('active_custom_miners')));

            // Start creating command options string
            $settings = null;
            $confArray = array();

            if ($minerSoftware != "cpuminer") {
                $confArray["api-listen"] = true;
                $confArray["api-allow"] = "W:127.0.0.1";
            }

            // Save manual/guided selection
            $this->redis->set('manual_options', $this->input->post('manual_options'));
            $this->redis->set('guided_options', $this->input->post('guided_options'));
            $dataObj->manual_options = $this->input->post('manual_options');
            $dataObj->guided_options = $this->input->post('guided_options');

            // General options
            // CPUMiner specific
            if ($minerSoftware == "cpuminer") {
                // Logging
                $minerdLog = false;
                if ($this->input->post('minerd_log')) {
                    $confArray["log"] = "/home/pirate/.piratecash/debug.log ";
                    $minerdLog = "/home/pirate/.piratecash/debug.log ";
                }
                $this->redis->set('minerd_log', $minerdLog);
                $dataObj->minerd_log = $minerdLog;
            }
            // CG/BFGminer specific
            else {
                // API Allow
                if ($this->input->post('minerd_api_allow_extra')) {
                    $confArray["api-allow"] .= "," . $this->input->post('minerd_api_allow_extra');
                }
                $this->redis->set('minerd_api_allow_extra', $this->input->post('minerd_api_allow_extra'));
                $dataObj->minerd_api_allow_extra = $this->input->post('minerd_api_allow_extra');

                // Logging
                if ($this->input->post('minerd_log')) {
                    $confArray["log-file"] = "/home/pirate/.piratecash/debug.log ";
                    $this->redis->set('minerd_log', $this->input->post('minerd_log'));
                    $dataObj->minerd_log = $this->input->post('minerd_log');
                } else {
                    $this->redis->del('minerd_log');
                }
            }

            // Append JSON conf
            $this->redis->set('minerd_append_conf', $this->input->post('minerd_append_conf'));
            $this->minerd_append_conf = $this->input->post('minerd_append_conf');

            if ($this->input->post('manual_options')) {
                // Manual options
                $settings = trim($this->input->post('minerd_manual_settings'));
                $this->redis->set('minerd_manual_settings', $settings);
                $dataObj->minerd_manual_settings = $settings;
            } else {
                // Guided options
                // CPUMiner specific
                if ($minerSoftware == "cpuminer") {
                    // Auto-detect
                    if ($this->input->post('minerd_autodetect')) {
                        $confArray["gc3355-detect"] = true;
                    }
                    $this->redis->set('minerd_autodetect', $this->input->post('minerd_autodetect'));
                    $dataObj->minerd_autodetect = $this->input->post('minerd_autodetect');

                    // Autotune
                    if ($this->input->post('minerd_autotune')) {
                        $confArray["gc3355-autotune"] = true;
                    }
                    $this->redis->set('minerd_autotune', $this->input->post('minerd_autotune'));
                    $dataObj->minerd_autotune = $this->input->post('minerd_autotune');

                    // Start frequency
                    if ($this->input->post('minerd_startfreq')) {
                        $confArray["freq"] = $this->input->post('minerd_startfreq');
                    }
                    $this->redis->set('minerd_startfreq', $this->input->post('minerd_startfreq'));
                    $dataObj->minerd_startfreq = $this->input->post('minerd_startfreq');
                }
                // CG/BFGminer specific
                else {
                    // Scrypt
                    if ($this->input->post('minerd_scrypt')) {
                        $confArray["scrypt"] = true;
                    }
                    $this->redis->set('minerd_scrypt', $this->input->post('minerd_scrypt'));
                    $dataObj->minerd_scrypt = $this->input->post('minerd_scrypt');

                    // Auto-detect
                    if ($this->input->post('minerd_autodetect')) {
                        $confArray["scan"] = "all";
                    }
                    $this->redis->set('minerd_autodetect', $this->input->post('minerd_autodetect'));
                    $dataObj->minerd_autodetect = $this->input->post('minerd_autodetect');
                }

                // Debug
                if ($this->input->post('minerd_debug')) {
                    $confArray["debug"] = true;
                }
                $this->redis->set('minerd_debug', $this->input->post('minerd_debug'));
                $this->minerd_debug = $this->input->post('minerd_debug');

                // Extra options
                if ($this->input->post('minerd_extraoptions')) {
                    $settings .= " " . $this->input->post('minerd_extraoptions') . " ";
                }
                $this->redis->set('minerd_extraoptions', $this->input->post('minerd_extraoptions'));
                $dataObj->minerd_extraoptions = $this->input->post('minerd_extraoptions');
            }

            // Add the pools to the command
            $poolsArray = array();

            // Global pool proxy
            if ($this->input->post('pool_global_proxy')) {
                $confArray["socks-proxy"] = $this->input->post('pool_global_proxy');
            }
            $this->redis->set('pool_global_proxy', $this->input->post('pool_global_proxy'));
            $dataObj->pool_global_proxy = $this->input->post('pool_global_proxy');

            $poolsArray = $this->util_model->parsePools($minerSoftware, $pools);

            $confArray['pools'] = $poolsArray;

            // Prepare JSON conf
            $jsonConfRedis = json_encode($confArray);
            $jsonConfFile = json_encode($confArray, JSON_PRETTY_PRINT);

            // Add JSON conf to miner command
            $exportConfigSettings = $settings;

            // End command options string

            $this->util_model->setPools($pools);

            $this->util_model->setCommandline($settings);
            $this->redis->set("minerd_json_settings", $jsonConfRedis);
            $dataObj->minerd_json_settings = $jsonConfRedis;
            $this->redis->set("minerd_autorecover", $this->input->post('minerd_autorecover'));
            $dataObj->minerd_autorecover = $this->input->post('minerd_autorecover');
            $this->redis->set("minerd_autorestart", $this->input->post('minerd_autorestart'));
            $dataObj->minerd_autorestart = $this->input->post('minerd_autorestart');
            $this->redis->set("minerd_autorestart_devices", $this->input->post('minerd_autorestart_devices'));
            $dataObj->minerd_autorestart_devices = $this->input->post('minerd_autorestart_devices');
            ($this->input->post('minerd_autorestart_time') > 0) ? $this->redis->set("minerd_autorestart_time", $this->input->post('minerd_autorestart_time')) : 600;
            $dataObj->minerd_autorestart_time = $this->input->post('minerd_autorestart_time');
            $this->redis->set("dashboard_refresh_time", $dashSettings);
            $dataObj->dashboard_refresh_time = $dashSettings;
            $this->redis->set("dashboard_temp", $dashboardTemp);
            $dataObj->dashboard_temp = $dashboardTemp;
            $this->redis->set("dashboard_skin", $dashboardSkin);
            $dataObj->dashboard_skin = $dashboardSkin;
            $this->redis->set("dashboard_table_records", $dashboardTableRecords);
            $dataObj->dashboard_table_records = $dashboardTableRecords;

            $this->redis->set("dashboard_devicetree", $dashboardDevicetree);
            $dataObj->dashboard_devicetree = $dashboardDevicetree;
            $this->redis->set("dashboard_box_profit", $dashboardBoxProfit);
            $dataObj->dashboard_box_profit = $dashboardBoxProfit;
            $this->redis->set("dashboard_box_local_miner", $dashboardBoxLocalMiner);
            $dataObj->dashboard_box_local_miner = $dashboardBoxLocalMiner;
            $this->redis->set("dashboard_box_local_pools", $dashboardBoxLocalPools);
            $dataObj->dashboard_box_local_pools = $dashboardBoxLocalPools;
            $this->redis->set("dashboard_box_network_details", $dashboardBoxNetworkDetails);
            $dataObj->dashboard_box_network_details = $dashboardBoxNetworkDetails;
            $this->redis->set("dashboard_box_network_pools_details", $dashboardBoxNetworkPoolsDetails);
            $dataObj->dashboard_box_network_pools_details = $dashboardBoxNetworkPoolsDetails;
            $this->redis->set("dashboard_box_chart_shares", $dashboardBoxChartShares);
            $dataObj->dashboard_box_chart_shares = $dashboardBoxChartShares;
            $this->redis->set("dashboard_box_chart_system_load", $dashboardBoxChartSystemLoad);
            $dataObj->dashboard_box_chart_system_load = $dashboardBoxChartSystemLoad;
            $this->redis->set("dashboard_box_chart_hashrates", $dashboardBoxChartHashrates);
            $dataObj->dashboard_box_chart_hashrates = $dashboardBoxChartHashrates;
            $this->redis->set("dashboard_box_scrypt_earnings", $dashboardBoxScryptEarnings);
            $dataObj->dashboard_box_scrypt_earnings = $dashboardBoxScryptEarnings;
            $this->redis->set("dashboard_box_log", $dashboardBoxLog);
            $dataObj->dashboard_box_log = $dashboardBoxLog;

            if ($this->redis->get("dashboard_coin_rates") !== json_encode($coinRates)) {
                $this->redis->set("dashboard_coin_rates", json_encode($coinRates));
                $dataObj->dashboard_coin_rates = json_encode($coinRates);
                $this->util_model->updateAltcoinsRates(true);
            }

            if ($mineraDonationTime) {
                $this->util_model->autoAddMineraPool();
            }

            $dataObj->minerd_pools = $this->util_model->getPools();

            // System settings
            // System hostname
            if ($this->input->post('system_hostname')) {
                $this->util_model->setSystemHostname($this->input->post('system_hostname'));
            }

            // Minera user password
            if ($this->input->post('system_password') && $this->input->post('system_password2')) {
                $this->util_model->setSystemUserPassword($this->input->post('system_password'));
            }

            // Set the System Timezone
            $timezone = $this->input->post('minera_timezone');
            $currentTimezone = $this->redis->get("minera_timezone");
            if ($currentTimezone != $timezone) {
                date_default_timezone_set($timezone);
                $this->util_model->setTimezone($timezone);
            }
            $dataObj->minera_timezone = $timezone;

            // Delay time
            $delay = 5;
            if ($this->input->post('minerd_delaytime')) {
                $delay = $this->input->post('minerd_delaytime');
                $this->redis->set("minerd_delaytime", $delay);
            }
            $dataObj->minerd_delaytime = $delay;

            // On boot extra commands
            $extracommands = false;
            if ($this->input->post('system_extracommands')) {
                $extracommands = $this->input->post('system_extracommands');
            }
            $this->redis->set("system_extracommands", $extracommands);
            $dataObj->system_extracommands = $extracommands;

            // Scheduled event
            $scheduledEventTime = false;
            $scheduledEventAction = false;
            $scheduledEventStartTime = false;
            if ($this->input->post('scheduled_event_time')) {
                $scheduledEventStartTime = time();
                $scheduledEventTime = $this->input->post('scheduled_event_time');
                $scheduledEventAction = $this->input->post('scheduled_event_action');
            }
            if ($this->redis->get("scheduled_event_time") != $scheduledEventTime) {
                $this->redis->set("scheduled_event_start_time", $scheduledEventStartTime);
            }
            $this->redis->set("scheduled_event_time", $scheduledEventTime);
            $dataObj->scheduled_event_time = $scheduledEventTime;
            $this->redis->set("scheduled_event_action", $scheduledEventAction);
            $dataObj->scheduled_event_action = $scheduledEventAction;

            // Startup script rc.local
            $this->util_model->saveStartupScript($minerSoftware, $delay, $extracommands);

            // Mobileminer
            // Enabled
            $mobileminerEnabled = false;
            if ($this->input->post('mobileminer_enabled')) {
                $mobileminerEnabled = $this->input->post('mobileminer_enabled');
            }
            $this->redis->set("mobileminer_enabled", $mobileminerEnabled);
            $dataObj->mobileminer_enabled = $mobileminerEnabled;

            // Sys name
            $mobileminerSysName = false;
            if ($this->input->post('mobileminer_system_name')) {
                $mobileminerSysName = $this->input->post('mobileminer_system_name');
            }
            $this->redis->set("mobileminer_system_name", $mobileminerSysName);
            $dataObj->mobileminer_system_name = $mobileminerSysName;

            // email
            $mobileminerEmail = false;
            if ($this->input->post('mobileminer_email')) {
                $mobileminerEmail = $this->input->post('mobileminer_email');
            }
            $this->redis->set("mobileminer_email", $mobileminerEmail);
            $dataObj->mobileminer_email = $mobileminerEmail;

            // Application key
            $mobileminerAppkey = false;
            if ($this->input->post('mobileminer_appkey')) {
                $mobileminerAppkey = $this->input->post('mobileminer_appkey');
            }
            $this->redis->set("mobileminer_appkey", $mobileminerAppkey);
            $dataObj->mobileminer_appkey = $mobileminerAppkey;

            $data['message'] = '<b>Success!</b> Settings saved!';
            $data['message_type'] = "success";

            if ($this->input->post('save_restart')) {
                $this->util_model->minerRestart();

                $this->session->set_flashdata('message', '<b>Success!</b> Settings saved and miner restarted!');
                $this->session->set_flashdata('message_type', 'success');
            } else {
                $this->session->set_flashdata('message', '<b>Success!</b> Settings saved!');
                $this->session->set_flashdata('message_type', 'success');
            }
        }

        if (is_array($extramessages)) {
            $this->session->set_flashdata('message', '<b>Warning!</b> ' . implode(" ", $extramessages));
            $this->session->set_flashdata('message_type', 'warning');
        }

        // Save export
        $this->redis->set("export_settings", json_encode($dataObj));

        // Publish stats to Redis
        $dataObj->minera_id = $mineraSystemId;
        $this->redis->publish("minera-channel", json_encode($dataObj));

        // Save current miner settings
        if ($this->input->get("save_config")) {
            unset($confArray['pools']);
            $lineConf = false;
            foreach ($confArray as $keyConf => $valueConf) {
                if ($valueConf != "1")
                    $lineConf .= " --" . $keyConf . "=" . $valueConf;
                else
                    $lineConf .= " --" . $keyConf;
            }
            $exportConfigSettings .= $lineConf;
            $dataObj = array("timestamp" => time(), "software" => $minerSoftware, "settings" => $exportConfigSettings, "pools" => $pools, "description" => false);
            $this->redis->command("HSET saved_miner_configs " . time() . " " . base64_encode(json_encode($dataObj)));
        }

        $this->redis->command("BGSAVE");

        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($dataObj));
    }

    /*
      // Enable disable browser mining
     */

    public function manage_browser_mining() {
        $this->util_model->isLoggedIn();
        $result = new stdClass();
        $error = new stdClass();

        if (!$this->input->post('action')) {
            $error->err = 'Action is required';
            echo json_encode($error);
            return false;
        }

        $action = $this->input->post('action');
        $threads = $this->input->post('threads');
        $threads = ($threads) ? $threads : 2;
        $enable = ($action === 'enable') ? true : false;

        $this->redis->set('browser_mining', $enable);
        $this->redis->set('browser_mining_threads', $threads);

        // log_message("error", $action);
        $result->action = $action;
        $result->threads = $threads;
        echo json_encode($result);
    }

    /*
      // Export the settings forcing download of JSON file
     */

    public function export() {
        $this->util_model->isLoggedIn();

        $o = $this->redis->get("export_settings");
        if ($this->util_model->isJson($o)) {
            $this->output
                    ->set_content_type('application/json')
                    ->set_header('Content-disposition: attachment; filename=minera-export.json')
                    ->set_output($o);
        } else
            return false;
    }

    /*
      // Shutdown controller (this should be in a different "system" controller file)
     */

    public function shutdown() {
        $this->util_model->isLoggedIn();

        if ($this->input->get('confirm')) {
            $data['message'] = "Please wait to unplug me.";
            $data['timer'] = true;
            $this->util_model->shutdown();
        } else {
            $data['title'] = "Are you sure?";
            $data['message'] = '<a href="' . site_url("app/shutdown") . '?confirm=1" class="btn btn-danger btn-lg"><i class="fa fa-check"></i> Yes, shutdown now</a>&nbsp;&nbsp;&nbsp;<a href="' . site_url("app/dashboard") . '" class="btn btn-primary btn-lg"><i class="fa fa-times"></i> No, thanks</a>';
            $data['timer'] = false;
        }

        $data['now'] = time();
        $data['sectionPage'] = 'lockscreen';
        $data['onloadFunction'] = false;
        $data['pageTitle'] = "Shutdown Minera";
        $data['messageEnd'] = "you can unplug me now.";
        $data['htmlTag'] = "lockscreen";
        $data['seconds'] = 30;
        $data['refreshUrl'] = false;
        $data['env'] = $this->config->item('ENV');

        $this->load->view('include/header', $data);
        $this->load->view('sysop', $data);
        $this->load->view('include/footer', $data);
    }

    /*
      // Reboot controller (this should be in a different "system" controller file)
     */

    public function reboot() {
        $this->util_model->isLoggedIn();

        if ($this->input->get('confirm')) {
            $data['refreshUrl'] = site_url("app/index");
            $data['message'] = "Please wait while I'm rebooting...";
            $data['timer'] = true;
            $this->util_model->reboot();
        } else {
            $data['title'] = "Are you sure?";
            $data['message'] = '<a href="' . site_url("app/reboot") . '?confirm=1" class="btn btn-danger btn-lg"><i class="fa fa-check"></i> Yes, reboot now</a>&nbsp;&nbsp;&nbsp;<a href="' . site_url("app/dashboard") . '" class="btn btn-primary btn-lg"><i class="fa fa-times"></i> No, thanks</a>';
            $data['timer'] = false;
        }

        $data['now'] = time();
        $data['sectionPage'] = 'lockscreen';
        $data['onloadFunction'] = false;
        $data['pageTitle'] = "Reboot Minera";
        $data['messageEnd'] = "here we go!";
        $data['htmlTag'] = "lockscreen";
        $data['seconds'] = 50;
        $data['env'] = $this->config->item('ENV');

        $this->load->view('include/header', $data);
        $this->load->view('sysop', $data);
        $this->load->view('include/footer', $data);
    }

    /*
      // Start miner controller (this should be in a different "system" controller file)
     */

    public function start_miner() {

        die('er');
        $this->util_model->isLoggedIn();

        if (!$this->util_model->isOnline())
            $this->util_model->minerStart();
        else {
            $this->session->set_flashdata('message', "<b>Warning!</b> Your miner is currently mining, before you can start it you need to stop it before, or try the restart link.");
        }


        redirect('app/dashboard');
    }

    /*
      // Stop miner controller (this should be in a different "system" controller file)
     */

    public function stop_miner() {
        $this->util_model->isLoggedIn();

        $this->util_model->minerStop();

        redirect('app/dashboard');
    }

    /*
      // Restart miner controller (this should be in a different "system" controller file)
     */

    public function restart_miner() {
        $this->util_model->isLoggedIn();

        $this->util_model->minerRestart();

        redirect('app/dashboard');
    }

    /*
      // Update controller (this should be in a different "system" controller file)
     */

    public function update() {
        $this->util_model->isLoggedIn();

        if ($this->util_model->checkUpdate()) {
            if ($this->input->get('confirm')) {
                $data['message'] = "Please wait while I'm upgrading the system...";
                $data['timer'] = true;
                $data['onloadFunction'] = "callUpdate()";
                $data['refreshUrl'] = site_url("app/index");
            } else {
                $data['title'] = "System update detected";
                $data['message'] = '<a href="' . site_url("app/update") . '?confirm=1"><button class="btn btn-danger btn-lg"><i class="fa fa-check"></i> Let me install the updates</button></a>&nbsp;&nbsp;&nbsp;<a href="' . site_url("app/dashboard") . '"><button class="btn btn-primary btn-lg"><i class="fa fa-times"></i> No, thanks</button></a><p><br /><small>Your local miner will be stopped during the update process. Minera will try to restart it after the update is complete.</small></p>';
                $data['timer'] = false;
                $data['onloadFunction'] = false;
                $data['refreshUrl'] = false;
            }

            $data['now'] = time();
            $data['sectionPage'] = 'lockscreen';
            $data['pageTitle'] = "Updating Minera";
            $data['messageEnd'] = "System updated!";
            $data['htmlTag'] = "lockscreen";
            $data['seconds'] = 200;
            $data['env'] = $this->config->item('ENV');

            $this->load->view('include/header', $data);
            $this->load->view('sysop', $data);
            $this->load->view('include/footer', $data);
        } else {
            redirect("app/dashboard");
        }
    }

    /*
      // API controller
     */

    public function api($command = false) {
        $this->util_model->isLoggedIn();

        $cmd = ($command) ? $command : $this->input->get('command');

        $o = '{ "Hello": "World" }';

        switch ($cmd) {
            case "get_blocks":
                $o = $this->util_model->getBlocks();
                break;
            case "update_minera":
                $o = $this->util_model->update();
                break;
            case "cron_unlock":
                $o = $this->redis->del("cron_lock");
                break;
            case "stats":
                $o = $this->util_model->getStats();
                break;
            case "miner_stats":
                $o = json_encode($this->util_model->getMinerStats());
                break;
            case "history_stats":
                $o = $this->util_model->getHistoryStats($this->input->get('type'));
                break;
            case "reset_action":
                $o = $this->util_model->reset($this->input->get('action'));
                $this->session->set_flashdata('message', '<b>Success!</b> Data has been reset.');
                $this->session->set_flashdata('message_type', 'success');
                break;
            case "factory_reset_action":
                $o = $this->util_model->factoryReset();
                $this->cron();
                $this->session->set_flashdata('message', '<b>Success!</b> Data has been reset.');
                $this->session->set_flashdata('message_type', 'success');
                break;
            case "profitability":
                $o = $this->util_model->getProfitability();
                break;
            case "import_file":
                $o = json_encode($this->util_model->importFile($this->input->post()));
                break;
            case "clone_system":
                $o = json_encode($this->util_model->cloneSystem());
                break;
            case "delete_config":
                $o = json_encode($this->util_model->deleteSavedConfig($this->input->get("id")));
                break;
            case "load_config":
                $o = json_encode($this->util_model->loadSavedConfig($this->input->get("id")));
                break;
            case "share_config":
                $o = json_encode($this->util_model->shareSavedConfig($this->input->post()));
                break;
            case "tail_log":
                $o = json_encode($this->util_model->tailFile($this->input->get('file'), ($this->input->get('lines')) ? $this->input->get('lines') : 5));
                break;
            case "box_status":
                $o = json_encode($this->util_model->setBoxStatus($this->input->get('id'), $this->input->get('status')));
                break;
            case "wallet_action":
                $action = ($this->input->get('action')) ? $this->input->get('action') : false;
                switch ($action) {
                    case "start":
                        $o = $this->util_model->walletStart();
                        break;
                    case "stop":
                        $o = $this->util_model->walletStop();
                        break;
                    case "restart":
                        $o = $this->util_model->walletRestart();
                        break;
                    default:
                        $o = json_encode(array("err" => true));
                }
                break;
            case "test":
                //$a = file_get_contents("api.json");
                //$o = $this->redis->command("BGSAVE"); //$this->util_model->checkCronIsRunning();
                //$this->util_model->updateAltcoinsRates(); //$this->util_model->refreshMinerConf(); //$o = json_encode($this->util_model->callMinerd()); //$this->util_model->getParsedStats($this->util_model->getMinerStats());
                break;
        }

        $this->output
                ->set_content_type('application/json')
                ->set_output($o);
    }

    /*
      // Stats controller get the live stats
     */

    public function stats() {
        if (!$this->session->userdata("loggedin")) {
            $stats = '{"notloggedin": true}';
        } else {
            $stats = $this->util_model->getStats();
        }

        $this->output
                ->set_content_type('application/json')
                ->set_output($stats);
    }

    public function lcd() {
        $o = $this->util_model->getStakingDashboard();
        $this->output
                ->set_content_type('application/json')
                ->set_output($o);
    }

    /*
      // Store controller Get the store stats from Redis
     */

    public function stored_stats() {
        $this->util_model->isLoggedIn();

        $storedStats = $this->util_model->getStoredStats(3600);

        $this->output
                ->set_content_type('application/json')
                ->set_output("[" . implode(",", $storedStats) . "]");
    }

    /*
      // Cron controller to be used to run scheduled tasks
     */

    public function cron() {
        if ($this->redis->get("cron_lock")) {
            log_message('error', "CRON locked waiting previous process to terminate...");
            return true;
        }

        if (($this->util_model->getSysUptime() + $this->redis->get('minerd_delaytime') ) <= 60) {
            log_message('error', "System just started, warming up...");
            return true;
        }

        $time_start = microtime(true);

        log_message('error', "--- START CRON TASKS ---");

        $this->redis->set("cron_lock", true);

        // Check and restart the minerd if it's dead
        if ($this->redis->get('minerd_autorecover')) {
            $this->util_model->checkMinerIsUp();
        }

        $now = time();
        $currentHour = date("H", $now);
        $currentMinute = date("i", $now);

        // Store the live stats
        $stats = $this->util_model->storeStats();

        // Publish stats to Redis
        $this->util_model->getStats();

        /*
          // Store the avg stats
         */
        // Store 5min avg
        if (($currentMinute % 5) == 0) {
            $this->util_model->storeAvgStats(300);
        }
        // Store 1hour avg
        if ($currentMinute == "00") {
            $this->util_model->storeAvgStats(3600);
        }
        // Store 1day avg
        if ($currentHour == "04" && $currentMinute == "00") {
            $this->util_model->storeAvgStats(86400);
        }

        // Store coins profitability
        if ($profit = $this->util_model->getProfitability()) {
            $this->redis->set("coins_profitability", $profit);
        }

        // Scheduled event
        $scheduledEventStartTime = $this->redis->get("scheduled_event_start_time");
        $scheduledEventTime = $this->redis->get("scheduled_event_time");
        $scheduledEventAction = $this->redis->get("scheduled_event_action");
        if ($scheduledEventTime > 0) {
            log_message("error", "TIME: " . time() . " - SCHEDULED START TIME: " . $scheduledEventStartTime);

            $timeToRunEvent = (($scheduledEventTime * 3600) + $scheduledEventStartTime);
            if (time() >= $timeToRunEvent) {

                log_message("error", "Running scheduled event ($timeToRunEvent) -> " . strtoupper($scheduledEventAction));

                $this->redis->set("scheduled_event_start_time", time());

                $this->redis->command("BGSAVE");

                log_message("error", "TIME: " . time() . " - AFTER SCHEDULED START TIME: " . $this->redis->get("scheduled_event_start_time"));

                if ($scheduledEventAction == "restart") {
                    $this->util_model->walletRestart();
                } else {
                    sleep(10);
                    $this->util_model->reboot();
                }
            }
        }


        // Use the live stats to check if autorestart is needed
        // (devices possible dead)
        $autorestartenable = $this->redis->get("minerd_autorestart");
        $autorestartdevices = $this->redis->get("minerd_autorestart_devices");
        $autorestarttime = $this->redis->get("minerd_autorestart_time");

        if ($autorestartenable && $autorestartdevices) {
            log_message('error', "Checking miner for possible dead devices...");

            // Use only if miner is online
            if ($this->util_model->isOnline()) {
                // Check if there is stats error
                if (isset($stats->error))
                    return false;

                // Get the max last_share time per device
                $lastshares = false;

                if (isset($stats->devices)) {
                    foreach ($stats->devices as $deviceName => $device) {
                        $lastshares[$deviceName] = $device->last_share;
                    }
                }

                // Check if there is any device with last_share time > 10minutes (possible dead device)
                if (is_array($lastshares)) {
                    $i = 0;
                    foreach ($lastshares as $deviceName => $lastshare) {
                        if ((time() - $lastshare) > $autorestarttime) {
                            log_message('error', "WARNING: Found device: " . $deviceName . " possible dead");
                            $i++;
                        }
                    }

                    // Check if dead devices are equal or more than the ones set
                    if ($i >= $autorestartdevices) {
                        // Restart miner
                        log_message('error', "ATTENTION: Restarting miner due to possible dead devices found - Threshold: " . $autorestartdevices . " Found: " . $i);
                        $this->util_model->minerRestart();
                    }
                }
            }
        }

        $this->redis->del("cron_lock");

        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start);

        log_message('error', "--- END CRON TASKS (" . round($execution_time, 2) . " secs) ---");
    }

    /*
      // Controllers for retro compatibility
     */

    public function cron_stats() {
        redirect('app/cron');
    }

}

/* End of file frontpage.php */
/* Location: ./application/controllers/frontpage.php */
