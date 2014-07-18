<?php if (!defined('BASEPATH')) die();

class App extends Main_Controller {

	public function __construct()
	{
		parent::__construct();

		// Set the general timezone
		$timezone = ($this->redis->get("minera_timezone")) ? $this->redis->get("minera_timezone") : 'Europe/Rome';
		date_default_timezone_set($timezone);

	}
	
	/*
	// Index/lock screen controller
	*/
	public function index()
	{	
		//$this->util_model->autoAddMineraPool();
		$data['htmlTag'] = "lockscreen";
		$data['pageTitle'] = "Welcome to Minera";
		$data['isOnline'] = $this->util_model->isOnline();
		$this->load->view('include/header', $data);
		$this->load->view('lockscreen');
	}
	
	/*
	// Login controller
	*/
	public function login()
	{		
		if ($this->input->post('password', true) && $this->input->post('password', true) == $this->redis->get('minera_password'))
		{
			$this->session->set_userdata("loggedin", 1);
			redirect('app/dashboard');
		}
		else
			redirect('app/index');
	}
	
	/*
	// Dashboard controller
	*/
	public function dashboard()
	{
		if (!$this->session->userdata("loggedin"))
			redirect('app/index');
		
		//$this->util_model->autoAddMineraPool();
		
		$data['minerdPools'] = json_decode($this->util_model->getPools());
		$data['btc'] = $this->util_model->getBtcUsdRates();
		$data['isOnline'] = $this->util_model->isOnline();
		$data['minerdLog'] = $this->redis->get('minerd_log');
		$data['savedFrequencies'] = $this->redis->get('current_frequencies');
		$data['htmlTag'] = "dashboard";
		$data['appScript'] = true;
		$data['settingsScript'] = false;
		$data['mineraUpdate'] = $this->util_model->checkUpdate();
		$data['dashboard_refresh_time'] = $this->redis->get("dashboard_refresh_time");
		$data['pageTitle'] = ($this->redis->get("mobileminer_system_name")) ? $this->redis->get("mobileminer_system_name")." > Minera - Dashboard" : "Minera - Dashboard";
		$data['minerdRunning'] = $this->redis->get("minerd_running_software");
		$data['minerdSoftware'] = $this->redis->get("minerd_software");
		
		$this->load->view('include/header', $data);
		$this->load->view('include/sidebar', $data);
		$this->load->view('frontpage', $data);
		$this->load->view('include/footer');
	}
	
	/*
	// Charts controller
	*/
	public function charts()
	{
		if (!$this->session->userdata("loggedin"))
			redirect('app/index');
		
		$data['btc'] = $this->util_model->getBtcUsdRates();
		$data['isOnline'] = $this->util_model->isOnline();
		$data['htmlTag'] = "charts";
		$data['chartsScript'] = true;
		$data['appScript'] = false;
		$data['settingsScript'] = false;
		$data['mineraUpdate'] = $this->util_model->checkUpdate();
		$data['pageTitle'] = ($this->redis->get("mobileminer_system_name")) ? $this->redis->get("mobileminer_system_name")." > Minera - Charts" : "Minera - Charts";
		$data['minerdRunning'] = $this->redis->get("minerd_running_software");
		$data['minerdSoftware'] = $this->redis->get("minerd_software");
		
		$this->load->view('include/header', $data);
		$this->load->view('include/sidebar', $data);
		$this->load->view('charts', $data);
		$this->load->view('include/footer');
	}

	/*
	// Settings controller
	*/
	public function settings()
	{
		if (!$this->session->userdata("loggedin"))
			redirect('app/index');
		
		$this->config->load('timezones');
		$data['timezones'] = $this->config->item("timezones");

		$data['message'] = false;
		$data['message_type'] = false;

		if ($this->input->post('save_password'))
		{
			$password = trim($this->input->post('password'));
			$password2 = trim($this->input->post('password2'));
			if (empty($password) && empty($password2))
			{
				$data['message'] = "<b>Warning!</b> Password can't be empty";
				$data['message_type'] = "warning";
			}
			elseif ($password != $password2)
			{
				$data['message'] = "<b>Warning!</b> Password mismatch";
				$data['message_type'] = "warning";				
			}
			else
			{
				$this->redis->set("minera_password", $password);
				$data['message'] = '<b>Success!</b> Password saved!';
				$data['message_type'] = "success";
			}
		}
		
		// Load Coin Rates
		$data['btc'] = $this->util_model->getBtcUsdRates();
		
		// Load miner settings
		$data['minerdCommand'] = $this->config->item("minerd_command");
		$data['minerdAutorestart'] = $this->redis->get('minerd_autorestart');
		$data['minerdAutorestartDevices'] = $this->redis->get('minerd_autorestart_devices');
		$data['minerdAutorestartTime'] = $this->redis->get('minerd_autorestart_time');
		$data['minerdAutorecover'] = $this->redis->get('minerd_autorecover');
		$data['minerdScrypt'] = $this->redis->get('minerd_scrypt');
		$data['minerdAutodetect'] = $this->redis->get('minerd_autodetect');
		$data['minerdAutotune'] = $this->redis->get('minerd_autotune');
		$data['minerdStartfreq'] = $this->redis->get('minerd_startfreq');
		$data['minerdExtraoptions'] = $this->redis->get('minerd_extraoptions');
		$data['minerdSoftware'] = $this->redis->get('minerd_software');
		$data['minerdLog'] = $this->redis->get('minerd_log');
		$data['minerdDebug'] = $this->redis->get('minerd_debug');
		$data['minerdManualSettings'] = $this->redis->get('minerd_manual_settings');
		$data['minerdSettings'] = $this->util_model->getCommandline();
		$data['minerdJsonSettings'] = $this->redis->get("minerd_json_settings");
		$data['minerdPools'] = $this->util_model->getPools();
		$data['minerdGuidedOptions'] = $this->redis->get("guided_options");
		$data['minerdManualOptions'] = $this->redis->get("manual_options");
		$data['minerdDelaytime'] = $this->redis->get("minerd_delaytime");
		$data['minerApiAllowExtra'] = $this->redis->get("minerd_api_allow_extra");
		
		//Load Dashboard settings
		$data['mineraStoredDonations'] = $this->util_model->getStoredDonations();
		$data['mineraDonationTime'] = $this->redis->get("minera_donation_time");
		$data['dashboard_refresh_time'] = $this->redis->get("dashboard_refresh_time");
		$dashboard_coin_rates = $this->redis->get("dashboard_coin_rates");
		$data['dashboard_coin_rates'] = (is_array(json_decode($dashboard_coin_rates))) ? json_decode($dashboard_coin_rates) : array();
		$data['cryptsy_data'] = $this->redis->get("cryptsy_data");
		$data['dashboardTemp'] = ($this->redis->get("dashboard_temp")) ? $this->redis->get("dashboard_temp") : "c";

		// Load System settings
		$data['mineraTimezone'] = $this->redis->get("minera_timezone");
		$data['systemExtracommands'] = $this->redis->get("system_extracommands");
		$data['scheduledEventStartTime'] = $this->redis->get("scheduled_event_start_time");
		$data['scheduledEventTime'] = $this->redis->get("scheduled_event_time");
		$data['scheduledEventAction'] = $this->redis->get("scheduled_event_action");
		$data['anonymousStats'] = $this->redis->get("anonymous_stats");
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
		$data['pageTitle'] = "Minera - Settings";
		$data['minerdRunning'] = $this->redis->get("minerd_running_software");
		$data['minerdSoftware'] = $this->redis->get("minerd_software");
		$data['donationProfitability'] = ($prof = $this->util_model->returnRemoteJsonConfig()) ? $prof->donation_profitability : "0.00075";
		
		$this->load->view('include/header', $data);
		$this->load->view('include/sidebar', $data);
		$this->load->view('settings', $data);
		$this->load->view('include/footer');
	}
	
	/*
	// Save Settings controller
	*/
	public function save_settings()
	{
		$extramessages = false;
			
		if ($this->input->post('save_settings'))
		{
			$minerSoftware = $this->input->post('minerd_software');
			$this->redis->set("minerd_software", $minerSoftware);
			$this->util_model->switchMinerSoftware($minerSoftware);
			
			$dashSettings = substr(trim($this->input->post('dashboard_refresh_time')), strpos(trim($this->input->post('dashboard_refresh_time')), ";") + 1);
			
			$mineraDonationTime = substr(trim($this->input->post('minera_donation_time')), strpos(trim($this->input->post('minera_donation_time')), ";") + 1);
			
			$coinRates = $this->input->post('dashboard_coin_rates');
			$this->redis->set("altcoins_update", (time()-3600));
			$dashboardTemp = $this->input->post('dashboard_temp');			

			$poolUrls = $this->input->post('pool_url');
			$poolUsernames = $this->input->post('pool_username');
			$poolPasswords = $this->input->post('pool_password');

			$pools = array();
			foreach ($poolUrls as $key => $poolUrl)
			{
				if ($poolUrl)
				{
					if (isset($poolUsernames[$key]) && isset($poolPasswords[$key]))
					{
						$pools[] = array("url" => $poolUrl, "username" => $poolUsernames[$key], "password" => $poolPasswords[$key]);
						/*if ($this->util_model->checkPool($poolUrl))
						{
						}
						else
						{
							$extramessages[] = "I cannot add this pool <strong>$poolUrl</strong> because it doesn't seem to be alive";
						}*/
					}
				}
			}

			// Start creating command options string
			$settings = null;
			$confArray = array();

			if ($minerSoftware != "cpuminer")
			{
				$confArray["api-listen"] = true;
				$confArray["api-allow"] = "W:127.0.0.1";
			}
			
			// Save manual/guided selection
			$this->redis->set('manual_options', $this->input->post('manual_options'));
			$this->redis->set('guided_options', $this->input->post('guided_options'));

			if ($this->input->post('manual_options'))
			{
				// Manual options
				$settings = trim($this->input->post('minerd_manual_settings'));
				$this->redis->set('minerd_manual_settings', $settings);

			}
			else
			{
				// Guided options
				
				// CPUMiner specific
				if ($minerSoftware == "cpuminer")
				{
					// Auto-detect
					if ($this->input->post('minerd_autodetect'))
					{
						$confArray["gc3355-detect"] = true;			
					}
					$this->redis->set('minerd_autodetect', $this->input->post('minerd_autodetect'));
	
					// Autotune
					if ($this->input->post('minerd_autotune'))
					{
						$confArray["gc3355-autotune"] = true;
					}
					$this->redis->set('minerd_autotune', $this->input->post('minerd_autotune'));
						
					// Start frequency
					if ($this->input->post('minerd_startfreq'))
					{
						$confArray["freq"] = $this->input->post('minerd_startfreq');
					}
					$this->redis->set('minerd_startfreq', $this->input->post('minerd_startfreq'));
					
					// Logging
					$minerdLog = false;
					if ($this->input->post('minerd_log'))
					{
						$confArray["log"] = $this->config->item("minerd_log_file");
						$minerdLog = $this->input->post('minerd_log');
					}
					$this->redis->set('minerd_log', $minerdLog);

				}
				// CG/BFGminer specific
				else
				{					
					// API Allow
					if ($this->input->post('minerd_api_allow_extra'))
					{
						$confArray["api-allow"] .= ",".$this->input->post('minerd_api_allow_extra');			
					}
					$this->redis->set('minerd_api_allow_extra', $this->input->post('minerd_api_allow_extra'));
					
					// Scrypt
					if ($this->input->post('minerd_scrypt'))
					{
						$confArray["scrypt"] = true;			
					}
					$this->redis->set('minerd_scrypt', $this->input->post('minerd_scrypt'));
					
					// Auto-detect
					if ($this->input->post('minerd_autodetect'))
					{
						$confArray["scan"] = "all";			
					}
					$this->redis->set('minerd_autodetect', $this->input->post('minerd_autodetect'));
	
					// Logging
					if ($this->input->post('minerd_log'))
					{
						$confArray["log-file"] = $this->config->item("minerd_log_file");
						$this->redis->set('minerd_log', $this->input->post('minerd_log'));
					}
					else
					{
						$this->redis->del('minerd_log');
					}

				}				

				// Debug
				if ($this->input->post('minerd_debug'))
				{
					$confArray["debug"] = true;
				}
				$this->redis->set('minerd_debug', $this->input->post('minerd_debug'));

				// Extra options
				if ($this->input->post('minerd_extraoptions'))
				{
					$settings .= " ".$this->input->post('minerd_extraoptions')." ";
				}
				$this->redis->set('minerd_extraoptions', $this->input->post('minerd_extraoptions'));				
				
			}
			
			// Add the pools to the command
			$poolsArray = array();
			foreach ($pools as $pool)
			{
				$addPools[] = " -o ".$pool['url']." -u ".$pool['username']." -p ".$pool['password'];
				$poolsArray[] = array("url" => $pool['url'], "user" => $pool['username'], "pass" => $pool['password']);
			}
			$confArray['pools'] = $poolsArray;
			
			// Prepare JSON conf
			$jsonConfRedis = json_encode($confArray);
			$jsonConfFile = json_encode($confArray, JSON_PRETTY_PRINT);
			
			// Add JSON conf to miner command
			$settings .= " -c ".$this->config->item("minerd_conf_file");
			// Save the JSON conf file
			file_put_contents($this->config->item("minerd_conf_file"), $jsonConfFile);

			// End command options string			

			$this->util_model->setPools($pools);
			
			if ($mineraDonationTime)
			{
				$this->util_model->autoAddMineraPool();
			}			
			
			$this->util_model->setCommandline($settings);
			$this->redis->set("minerd_json_settings", $jsonConfRedis);
			$this->redis->set("minerd_autorecover", $this->input->post('minerd_autorecover'));
			$this->redis->set("minerd_autorestart", $this->input->post('minerd_autorestart'));
			$this->redis->set("minerd_autorestart_devices", $this->input->post('minerd_autorestart_devices'));
			($this->input->post('minerd_autorestart_time') > 0) ? $this->redis->set("minerd_autorestart_time", $this->input->post('minerd_autorestart_time')) : 600;
			$this->redis->set("minera_donation_time", $mineraDonationTime);
			$this->redis->set("dashboard_refresh_time", $dashSettings);
			$this->redis->set("dashboard_coin_rates", json_encode($coinRates));
			$this->redis->set("dashboard_temp", $dashboardTemp);
			
			// System settings
			
			// Set the System Timezone
			$timezone = $this->input->post('minera_timezone');
			$currentTimezone = $this->redis->get("minera_timezone");
			if ($currentTimezone != $timezone)
			{
				date_default_timezone_set($timezone);
				$this->util_model->setTimezone($timezone);
			}
			
			// Delay time
			$delay = 5;
			if ($this->input->post('minerd_delaytime'))
			{
				$delay = $this->input->post('minerd_delaytime');
				$this->redis->set("minerd_delaytime", $delay);
			}

			// On boot extra commands
			$extracommands = false;
			if ($this->input->post('system_extracommands'))
			{
				$extracommands = $this->input->post('system_extracommands');
			}
			$this->redis->set("system_extracommands", $extracommands);
			
			// Scheduled event
			$scheduledEventTime = false; $scheduledEventAction = false; $scheduledEventStartTime = false;
			if ($this->input->post('scheduled_event_time'))
			{
				$scheduledEventStartTime = time();
				$scheduledEventTime = $this->input->post('scheduled_event_time');
				$scheduledEventAction = $this->input->post('scheduled_event_action');
			}
			if ($this->redis->get("scheduled_event_time") != $scheduledEventTime)
				$this->redis->set("scheduled_event_start_time", $scheduledEventStartTime);
			$this->redis->set("scheduled_event_time", $scheduledEventTime);
			$this->redis->set("scheduled_event_action", $scheduledEventAction);
			
			// Anonymous stats
			$anonymousStats = false;
			if ($this->input->post('anonymous_stats'))
			{
				$anonymousStats = $this->input->post('anonymous_stats');
				if (!$this->redis->get("minera_system_id"))
				{
					$mineraSystemId = $this->util_model->generateMineraId();
					$this->redis->set("minera_system_id", $mineraSystemId);
				}
			}
			$this->redis->set("anonymous_stats", $anonymousStats);
						
			// Startup script rc.local
			$this->util_model->saveStartupScript($delay, $extracommands);
			
			// Mobileminer
			// Enabled
			$mobileminerEnabled = false;
			if ($this->input->post('mobileminer_enabled'))
			{
				$mobileminerEnabled = $this->input->post('mobileminer_enabled');
			}
			$this->redis->set("mobileminer_enabled", $mobileminerEnabled);
			// Sys name
			$mobileminerSysName = false;
			if ($this->input->post('mobileminer_system_name'))
			{
				$mobileminerSysName = $this->input->post('mobileminer_system_name');
			}
			$this->redis->set("mobileminer_system_name", $mobileminerSysName);
			// email
			$mobileminerEmail = false;
			if ($this->input->post('mobileminer_email'))
			{
				$mobileminerEmail = $this->input->post('mobileminer_email');
			}
			$this->redis->set("mobileminer_email", $mobileminerEmail);
			// Application key
			$mobileminerAppkey = false;
			if ($this->input->post('mobileminer_appkey'))
			{
				$mobileminerAppkey = $this->input->post('mobileminer_appkey');
			}
			$this->redis->set("mobileminer_appkey", $mobileminerAppkey);

			$data['message'] = '<b>Success!</b> Settings saved!';
			$data['message_type'] = "success";
						
			if ($this->input->post('save_restart'))
			{
				$this->util_model->minerRestart();
				
				$this->session->set_flashdata('message', '<b>Success!</b> Settings saved and miner restarted!');
				$this->session->set_flashdata('message_type', 'success');
			}
			else
			{
				$this->session->set_flashdata('message', '<b>Success!</b> Settings saved!');
				$this->session->set_flashdata('message_type', 'success');
			}

		}
		
		if (is_array($extramessages))
		{
			$this->session->set_flashdata('message', '<b>Warning!</b> '.implode(" ", $extramessages));
			$this->session->set_flashdata('message_type', 'warning');
		}
	}
	
	/*
	// Shutdown controller (this should be in a different "system" controller file)
	*/
	public function shutdown()
	{	
		if ($this->input->get('confirm'))
		{
			$data['message'] = "Please wait to unplug me.";
			$data['timer'] = true;
			$this->util_model->shutdown();
		}
		else
		{
			$data['title'] = "Are you sure?";
			$data['message'] = '<a href="'.site_url("app/shutdown").'?confirm=1"><button class="btn btn-default btn-lg"><i class="fa fa-check"></i> Yes, shutdown now</button></a>&nbsp;&nbsp;&nbsp;<a href="'.site_url("app/dashboard").'"><button class="btn btn-default btn-lg"><i class="fa fa-times"></i> No, thanks</button></a>';
			$data['timer'] = false;
		}
		
		$data['onloadFunction'] = false;
		$data['pageTitle'] = "Shutdown Minera";
		$data['messageEnd'] = "you can unplug me now.";
		$data['htmlTag'] = "lockscreen";
		$data['seconds'] = 30;
		$data['refreshUrl'] = false;
		$this->load->view('include/header', $data);
		$this->load->view('sysop', $data);
	}

	/*
	// Reboot controller (this should be in a different "system" controller file)
	*/
	public function reboot()
	{	
		if ($this->input->get('confirm'))
		{
			$data['message'] = "Please wait while I'm rebooting...";
			$data['timer'] = true;
			$this->util_model->reboot();
		}
		else
		{
			$data['title'] = "Are you sure?";
			$data['message'] = '<a href="'.site_url("app/reboot").'?confirm=1"><button class="btn btn-default btn-lg"><i class="fa fa-check"></i> Yes, reboot now</button></a>&nbsp;&nbsp;&nbsp;<a href="'.site_url("app/dashboard").'"><button class="btn btn-default btn-lg"><i class="fa fa-times"></i> No, thanks</button></a>';
			$data['timer'] = false;
		}
		
		$data['onloadFunction'] = false;
		$data['pageTitle'] = "Reboot Minera";
		$data['messageEnd'] = "here we go!";
		$data['htmlTag'] = "lockscreen";
		$data['seconds'] = 50;
		$data['refreshUrl'] = site_url("app/index");
		$this->load->view('include/header', $data);
		$this->load->view('sysop', $data);
	}

	/*
	// Start miner controller (this should be in a different "system" controller file)
	*/
	public function start_miner()
	{
		if (!$this->session->userdata("loggedin"))
			redirect('app/index');
		
		if (!$this->util_model->isOnline())
			$this->util_model->minerStart();
		else
		{
			$this->session->set_flashdata('message', "<b>Warning!</b> Your miner is currently mining, before you can start it you need to stop it before, or try the restart link.");
		}
			
		
		redirect('app/dashboard');
	}

	/*
	// Stop miner controller (this should be in a different "system" controller file)
	*/
	public function stop_miner()
	{
		if (!$this->session->userdata("loggedin"))
			redirect('app/index');
		
		$this->util_model->minerStop();
		
		redirect('app/dashboard');
	}
	
	/*
	// Restart miner controller (this should be in a different "system" controller file)
	*/
	public function restart_miner()
	{
		if (!$this->session->userdata("loggedin"))
			redirect('app/index');
		
		$this->util_model->minerRestart();
		
		redirect('app/dashboard');
	}
	
	/*
	// Update controller (this should be in a different "system" controller file)
	*/
	public function update()
	{
		if ($this->util_model->checkUpdate())
		{
			if ($this->input->get('confirm'))
			{
				$data['message'] = "Please wait while I'm upgrading the system...";
				$data['timer'] = true;
				$data['onloadFunction'] = "callUpdate()";
				$data['refreshUrl'] = site_url("app/index");
			}
			else
			{
				$data['title'] = "System update detected";
				$data['message'] = '<a href="'.site_url("app/update").'?confirm=1"><button class="btn btn-default btn-lg"><i class="fa fa-check"></i> Let me install the updates</button></a>&nbsp;&nbsp;&nbsp;<a href="'.site_url("app/dashboard").'"><button class="btn btn-default btn-lg"><i class="fa fa-times"></i> No, thanks</button></a>';
				$data['timer'] = false;
				$data['onloadFunction'] = false;
				$data['refreshUrl'] = false;
			}
			
			$data['pageTitle'] = "Updating Minera";
			$data['messageEnd'] = "System updated!";
			$data['htmlTag'] = "lockscreen";
			$data['seconds'] = 180;
			$this->load->view('include/header', $data);
			$this->load->view('sysop', $data);
		}
		else
		{
			redirect("app/dashboard");
		}
	}

	/*
	// API controller
	*/
	public function api($command = false)
	{
		$cmd = ($command) ? $command : $this->input->get('command');
		
		switch($cmd)
		{
			case "save_current_freq":
				$o = $this->util_model->saveCurrentFreq();
			break;
			case "select_pool":
				$o = json_encode($this->util_model->selectPool($this->input->get('poolId')));
				// Give the miner the time to refresh
				sleep(3);
			break;
			case "update_minera":
				$o = $this->util_model->update();
			break;
			case "miner_stats":
				$o = json_encode($this->util_model->getMinerStats());
			break;
			case "notify_mobileminer":
				$o = $this->util_model->callMobileminer();
			break;
			case "history_stats":
				$o = $this->util_model->getHistoryStats($this->input->get('type'));
			break;
			case "miner_action":
				$action = ($this->input->get('action')) ? $this->input->get('action') : false;
				switch($action)
				{					
					case "start":
						$o = $this->util_model->minerStart();
					break;
					case "stop":
						$o = $this->util_model->minerStop();
					break;
					case "restart":
						$o = $this->util_model->minerRestart();
					break;
					default:
						$o = json_encode(array("err" => true));
				}
			break;
			case "test":
				//$this->load->model('bfgminer_model');
				$o = $this->util_model->sendAnonymousStats(123, "hello world!"); //json_encode($this->bfgminer_model->callMinerd()); //$this->util_model->getParsedStats($this->util_model->getMinerStats());
			break;
		}
		
		$this->output
			->set_content_type('application/json')
			->set_output($o);
	}
	
	/*
	// Stats controller get the live stats
	*/
	public function stats()
	{
		$stats = $this->util_model->getStats();
		
		$this->output
			->set_content_type('application/json')
			->set_output($stats);
	}
	
	/*
	// Store controller Get the store stats from Redis
	*/
	public function stored_stats()
	{
		$storedStats = $this->util_model->getStoredStats(3600);
		
		$this->output
			->set_content_type('application/json')
			->set_output("[".implode(",", $storedStats)."]");
	}	

	/*
	// Cron controller to be used to run scheduled tasks
	*/
	public function cron()
	{
		if ($this->redis->get("cron_lock"))
		{
			log_message('error', "CRON locked waiting previous process to terminate...");			
			return true;
		}
	
		$time_start = microtime(true); 
			
		log_message('error', "--- START CRON TASKS ---");
			
		$this->redis->set("cron_lock", true);
			
		// Check and restart the minerd if it's dead
		if ($this->redis->get('minerd_autorecover'))
		{
			$this->util_model->checkMinerIsUp();	
		}
		
		$now = time();
		$currentHour = date("H", $now);
		$currentMinute = date("i", $now);
		
		// Refresh Cryptsydata if needed
		$this->util_model->refreshCryptsyData();
		$this->util_model->updateAltcoinsRates();
						
		// Store the live stats
		$stats = $this->util_model->storeStats();

		/*
		// Store the avg stats
		*/
		// Store 5min avg
		if ( ($currentMinute%5) == 0)
		{
			$this->util_model->storeAvgStats(300);
		}
		// Store 1hour avg
		if ( $currentMinute == "00")
		{
			$this->util_model->storeAvgStats(3600);
		}
		// Store 1day avg
		if ( $currentHour == "04" && $currentMinute == "00")
		{
			$this->util_model->storeAvgStats(86400);
		}
		
		// Activate/Deactivate time donation pool if enable
		if ($this->util_model->isOnline() && isset($stats->pool_donation_id))
		{		
			$donationTime = $this->redis->get("minera_donation_time");
			if ($donationTime > 0)
			{
				$currentHr = (isset($stats->pool->hashrate)) ? $stats->pool->hashrate : 0;
				$poolDonationId = $stats->pool_donation_id;
				$donationTimeStarted = ($this->redis->get("donation_time_started")) ? $this->redis->get("donation_time_started") : false;

				$donationTimeDoneToday = ($this->redis->get("donation_time_done_today")) ? $this->redis->get("donation_time_done_today") : false;

				$donationStartHour = "04";
				$donationStartMinute = "10";
				$donationStopHour = date("H", ($donationTimeStarted + $donationTime*60));
				$donationStopMinute = date("i", ($donationTimeStarted + $donationTime*60));
				
				// Delete the donation-done flag after 24h
				if ($now >= ($donationTimeDoneToday+86400))
				{
					$this->redis->del("donation_time_started");
					$this->redis->del("donation_time_done_today");	
					$donationTimeStarted = false;
					$donationTimeDoneToday = false;
				}
				
				// Stop time donation
				if ($donationTimeStarted > 0 && (int)$currentHour >= (int)$donationStopHour && (int)$currentMinute >= (int)$donationStopMinute)
				{
					$this->redis->del("donation_time_started");
					$donationTimeStarted = false;
					$this->util_model->selectPool(0);
					log_message("error", "[Donation-time] Terminated... Switching back to main pool ID [0]");
				}

				if ($donationTimeStarted > 0)
				{
					// Time donation in progress
					$remain = round(((($donationTime*60) - ($now - $donationTimeStarted))/60));
					$this->redis->set("donation_time_remain", $remain);
					log_message("error", "[Donation time] In progress..." . $remain . " minutes remaing..." );
				}

				// Start time donation
				if ($donationTimeDoneToday === false && ((int)$currentHour >= (int)$donationStartHour && (int)$currentMinute >= (int)$donationStartMinute))
				{
					// Starting time donation
					$this->util_model->selectPool($poolDonationId);
					$this->redis->set("donation_time_started", $now);
					
					// This prevent any re-activation for the current day
					$this->redis->set("donation_time_done_today", $now);
					
					$this->redis->command("LPUSH saved_donations ".$now.":".$donationTime.":".$currentHr);
					
					log_message("error", "[Donation time] Started... (for ".$donationTime." minutes) - Switching to donation pool ID [".$poolDonationId."]");
				}
			}
		}

		// Scheduled event
		$scheduledEventStartTime = $this->redis->get("scheduled_event_start_time");
		$scheduledEventTime = $this->redis->get("scheduled_event_time");
		$scheduledEventAction = $this->redis->get("scheduled_event_action");
		if ($scheduledEventTime > 0)
		{
			$timeToRunEvent = (($scheduledEventTime*3600) + $scheduledEventStartTime);
			if (time() >= $timeToRunEvent)
			{
				log_message("error", "Running scheduled event -> ".strtoupper($scheduledEventAction));
				$this->redis->set("scheduled_event_start_time", time());
				if ($scheduledEventAction == "restart")
				{
					$this->util_model->minerRestart();
				}
				else
				{
					$this->util_model->reboot();
				}
			}
		}
		
		// Send anonymous stats
		$anonynousStatsEnabled = $this->redis->get("anonymous_stats");
		$mineraSystemId = $this->redis->get("minera_system_id");

		if ($this->util_model->isOnline() && $anonynousStatsEnabled && $mineraSystemId)
		{
			if (isset($stats->totals->hashrate))
				$totalHashrate = $stats->totals->hashrate;
			if (isset($stats->devices))
				$totalDevices = count(($stats->devices));

			$minerdRunning = $this->redis->get("minerd_running_software");

			$anonStats = array("id" => $mineraSystemId, "hashrate" => $totalHashrate, "devices" => $totalDevices, "miner" => $minerdRunning, "timestamp" => time());
			
			if ( $currentMinute == "00")
			{
				$this->util_model->sendAnonymousStats($mineraSystemId, $anonStats);
			}
		}
				
		// Use the live stats to check if autorestart is needed
		// (devices possible dead)
		$autorestartenable = $this->redis->get("minerd_autorestart");
		$autorestartdevices = $this->redis->get("minerd_autorestart_devices");
		$autorestarttime = $this->redis->get("minerd_autorestart_time");

		if ($autorestartenable && $autorestartdevices)
		{
			log_message('error', "Checking miner for possible dead devices...");
		
			// Use only if miner is online
			if ($this->util_model->isOnline())
			{
				// Check if there is stats error
				if (isset($stats->error))
					return false;
				
				// Get the max last_share time per device
				$lastshares = false;
				
				if (isset($stats->devices))
				{
					foreach ($stats->devices as $deviceName => $device)
					{
						$lastshares[$deviceName] = $device->last_share;
					}
				}
				
				// Check if there is any device with last_share time > 10minutes (possible dead device)
				if (is_array($lastshares))
				{
					$i = 0;
					foreach ($lastshares as $deviceName => $lastshare)
					{
						if ( (time() - $lastshare) > $autorestarttime )
						{
							log_message('error', "WARNING: Found device: ".$deviceName." possible dead");
							$i++;
						}
					}
					
					// Check if dead devices are equal or more than the ones set
					if ($i >= $autorestartdevices)
					{
						// Restart miner
						log_message('error', "ATTENTION: Restarting miner due to possible dead devices found - Threshold: ".$autorestartdevices." Found: ".$i);
						$this->util_model->minerRestart();
					}
				}
			}
		}
		
		// Call Mobileminer if enabled
		$this->util_model->callMobileminer();

		$this->redis->del("cron_lock");

		$time_end = microtime(true);
		$execution_time = ($time_end - $time_start);
		
		log_message('error', "--- END CRON TASKS (".round($execution_time, 2)." secs) ---");
	}
	
	/*
	// Controllers for retro compatibility
	*/
	public function cron_stats()
	{
		redirect('app/cron');
	}

}

/* End of file frontpage.php */
/* Location: ./application/controllers/frontpage.php */
