<?php
/*
 * Utit_model
 * Utility model for minera
 *
 * @author michelem
 */
class Util_model extends CI_Model {

	private $_minerdSoftware;

	public function __construct()
	{
		// load Miner Model
		// Switch model for CGMiner/BFGMiner/CPUMiner
		$this->switchMinerSoftware();
		parent::__construct();
	}

	public function isLoggedIn() 
	{
		if (!$this->session->userdata("loggedin"))
		{
			redirect('app/index');
			return false;
		}
		
		return true;
	}
	
	public function switchMinerSoftware($software = false, $network = false)
	{
		if ($this->redis->get("minerd_use_root"))
		{
			$this->config->set_item('system_user', 'root');		
		}
		else
		{
			$this->config->set_item('system_user', 'minera');
		}
		
		if ($software)
			$this->_minerdSoftware = $software;
		else
			$this->_minerdSoftware = $this->redis->get('minerd_software');
			
		if ($this->_minerdSoftware == "bfgminer")
		{
			// Config for Bfgminer
			$this->config->set_item('minerd_binary', 'bfgminer');
			$this->config->set_item('screen_command', '/usr/bin/screen -dmS bfgminer');
			$this->config->set_item('screen_command_stop', '/usr/bin/screen -S bfgminer -X quit');
			$this->config->set_item('minerd_command', FCPATH.'minera-bin/bfgminer');
			$this->config->set_item('minerd_log_file', '/var/log/minera/bfgminer.log');
			$this->config->set_item('minerd_special_log', false);
			$this->config->set_item('minerd_log_url', 'application/logs/bfgminer.log');
			$this->load->model('cgminer_model', 'miner');			
		}
		elseif ($this->_minerdSoftware == "cgminer")
		{
			// Config for Cgminer
			$this->config->set_item('minerd_binary', 'cgminer');
			$this->config->set_item('screen_command', '/usr/bin/screen -dmS cgminer');
			$this->config->set_item('screen_command_stop', '/usr/bin/screen -S cgminer -X quit');
			$this->config->set_item('minerd_command', FCPATH.'minera-bin/cgminer');
			$this->config->set_item('minerd_log_file', '/var/log/minera/cgminer.log');
			$this->config->set_item('minerd_special_log', true);
			$this->config->set_item('minerd_log_url', 'application/logs/cgminer.log');
			$this->load->model('cgminer_model', 'miner');
		}
		elseif ($this->_minerdSoftware == "cgdmaxlzeus")
		{
			// Config for Cgminer Dmal Zeus
			$this->config->set_item('minerd_binary', 'cgminer-dmaxl-zeus');
			$this->config->set_item('screen_command', '/usr/bin/screen -dmS cgminerdmaxlzeus');
			$this->config->set_item('screen_command_stop', '/usr/bin/screen -S cgminerdmaxlzeus -X quit');
			$this->config->set_item('minerd_command', FCPATH.'minera-bin/cgminer-dmaxl-zeus');
			$this->config->set_item('minerd_log_file', '/var/log/minera/cgminerdmaxlzeus.log');
			$this->config->set_item('minerd_special_log', true);
			$this->config->set_item('minerd_log_url', 'application/logs/cgminerdmaxlzeus.log');
			$this->load->model('cgminer_model', 'miner');
		}
		elseif ($this->_minerdSoftware == "cpuminer")
		{
			// Config for Cpuminer-gc3355
			$this->config->set_item('minerd_binary', 'minerd');
			$this->config->set_item('screen_command', '/usr/bin/screen -dmS cpuminer');
			$this->config->set_item('screen_command_stop', '/usr/bin/screen -S cpuminer -X quit');
			$this->config->set_item('minerd_command', FCPATH.'minera-bin/minerd');
			$this->config->set_item('minerd_log_file', '/var/log/minera/cpuminer.log');
			$this->config->set_item('minerd_special_log', false);
			$this->config->set_item('minerd_log_url', 'application/logs/cpuminer.log');
			$this->load->model('cpuminer_model', 'miner');
		}
		else
		{
			// Config for any custom miner
			$this->config->set_item('minerd_binary', $this->_minerdSoftware);
			$this->config->set_item('screen_command', '/usr/bin/screen -dmS '.$this->_minerdSoftware);
			$this->config->set_item('screen_command_stop', '/usr/bin/screen -S '.$this->_minerdSoftware.' -X quit');
			$this->config->set_item('minerd_command', FCPATH.'minera-bin/custom/'.$this->_minerdSoftware);
			$this->config->set_item('minerd_log_file', '/var/log/minera/'.$this->_minerdSoftware.'.log');
			$this->config->set_item('minerd_special_log', true);
			$this->config->set_item('minerd_log_url', 'application/logs/'.$this->_minerdSoftware.'.log');
			$this->load->model('cgminer_model', 'miner');
		}
		
		if ($network) $this->load->model('cgminer_model', 'network_miner');
		
		return true;
	}
	
	/*
	//
	// Stats related stuff
	//
	*/
	
	// Get the live stats from miner
	public function getStats()
	{
		$a = new stdClass();
		$altcoinData = $this->getAltcoinsRates();
		$btcData = $this->getBtcUsdRates();
		
		if ($this->isOnline())
		{
			$miner = $this->getMinerStats();

			$a = json_decode($this->getParsedStats($miner));
			
			if (is_object($a) && is_object($miner))
			{			
				$a->pools = $miner->pools;	
				
				// Encode and save the latest
				$l = json_encode($a);
				$this->redis->set("latest_stats", $l);
			}
			else
			{
				if ($latestSaved = $this->redis->get("latest_stats"))
				{
					$a = json_decode($latestSaved);
				}
				else
				{
					$a->error = true;
					$a->msg = "There are no stats to be displayed.";
				}
			}
		}
		else
		{
			$a->notrunning = true;
		}
		
		$a->network_miners = $this->getNetworkMinerStats(true);
		
		// Add local algo used
		$a->algo = $this->checkAlgo(true);
		
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
		$a->profits = json_decode($this->redis->get('coins_profitability'));

		return json_encode($a);		
	}
	
	public function getNetworkMinerStats($parsed)
	{
		$a = array();
		
		$netMiners = $this->getNetworkMiners();
		
		if (count($netMiners) > 0)
		{
			$this->load->model('cgminer_model', 'network_miner');
			
			foreach ($netMiners as $netMiner) {
				$a[$netMiner->name] = new stdClass();

				if ($this->checkNetworkDevice($netMiner->ip, $netMiner->port)) 
				{
					$n = $this->getMinerStats($netMiner->ip.":".$netMiner->port);

					if ($parsed === false)
						$a[$netMiner->name] = $n;
					else {
						if ($n) {
							$a[$netMiner->name] = json_decode($this->getParsedStats($n, true));
							$a[$netMiner->name]->pools = $n->pools;
						}
					}
				}
				// Add config data
				$a[$netMiner->name]->config = array('ip' => $netMiner->ip, 'port' => $netMiner->port, 'algo' => $netMiner->algo);
			}
		}

		return $a;
	}
	
	// Get the specific miner stats
	public function getMinerStats($network = false)
	{
		$tmpPools = null; $pools = array();
		
		if ($this->isOnline($network))
		{
			$a = ($network) ? $this->network_miner->callMinerd(false, $network) : $this->miner->callMinerd();

			if (is_object($a))
			{
				if ($this->_minerdSoftware == "cpuminer" && !$network) {
					$pools = (isset($a->pools)) ? $a->pools : false;
				} 
				else
				{
					$devicePoolActives = false;
					
					// Get the real active pools
					if (isset($a->devs[0]->DEVS))
					{
						$devicePoolIndex = array();
						
						foreach ($a->devs[0]->DEVS as $device)
						{			
							// Check the real active pool
							$devicePoolIndex[] = $device->{'Last Share Pool'};
						}				
						
						$devicePoolActives = array_count_values($devicePoolIndex);					
					}

					// Parse cg/bfgminer pools to be the same as cpuminer
					$tmpPools = (isset($a->pools[0]->POOLS)) ? $a->pools[0]->POOLS : false;
					if ($tmpPools)
					{
						foreach ($tmpPools as $poolIndex => $tmpPool)
						{
							if (isset($tmpPool->Works)) {
								$getworks = $tmpPool->Works;
							} elseif (isset($tmpPool->Getworks)) {
								$getworks = $tmpPool->Getworks;
							} else {
								$getworks = false;
							}
							
							$stats = new stdClass();
							$stats->start_time = false;
							$stats->accepted = $tmpPool->Accepted;
							$stats->rejected = $tmpPool->Rejected;
							$stats->shares = $getworks;
							$stats->stop_time = false;
							$stats->stats_id = 1;
							
							$poolActive = ($devicePoolActives && array_key_exists($poolIndex, $devicePoolActives)) ? true : false;
							
							$newpool = new stdClass();
							$newpool->priority = $tmpPool->Priority;
							$newpool->url = $tmpPool->URL;
							$newpool->active = $poolActive; //$tmpPool->{'Stratum Active'};
							$newpool->user = $tmpPool->User;
							$newpool->pass = false;
							$newpool->stats = array($stats);
							$newpool->stats_id = 1;
							$newpool->alive = ($tmpPool->Status == "Alive") ? 1 : 0;
							
							$pools[] = $newpool;
							
							unset($newpool);
							unset($stats);
						}
					}
				}

				// Add Alive status for cpuminer pools
				if ($this->_minerdSoftware == "cpuminer")
				{
					if ($pools)
					{
						foreach ($pools as $pool)
						{
							if (isset($pool->url))
								$pool->alive = $this->checkPool($pool->url);
							else
								$pool->alive = false;
						}	
					}
				}
				
				$a->original_pools = $tmpPools;
				$a->pools = $pools;
				return $a;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
		
		return false;
	}
	
	/*
	// Parse the miner stats to add devices
	// with a summary total and active pool
	*/
	public function getParsedStats($stats, $network = false)
	{		
		$d = 0; $tdevice = array(); $tdtemperature = 0; $tdfrequency = 0; $tdaccepted = 0; $tdrejected = 0; $tdhwerrors = 0; $tdshares = 0; $tdhashrate = 0; $devicePoolActives = false;
		$return = false;
		
		if (isset($stats->start_time))
		{
			$return['start_time'] = $stats->start_time;
		}
		elseif (isset($stats->summary[0]->SUMMARY[0]->Elapsed))
		{
			$return['start_time'] = round((time() - $stats->summary[0]->SUMMARY[0]->Elapsed), 0);
		}

		if (isset($stats->err))
		{
			$return['err'] = $stats->err;
		}
		
		$poolHashrate = 0;

		// CPUminer devices stats
		if ($this->_minerdSoftware == "cpuminer" && !$network)
		{
			if (isset($stats->devices))
			{
				foreach ($stats->devices as $name => $device)
				{
					$d++; $c = 0; $tcfrequency = 0; $tcaccepted = 0; $tcrejected = 0; $tchwerrors = 0; $tcshares = 0; $tchashrate = 0; $tclastshares = array();
					
					if ($device->chips)
					{
						foreach ($device->chips as $chip)
						{
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
					$return['devices'][$name]['frequency'] = ($c > 0) ? round(($tcfrequency/$c), 0) : 0;
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
				$return['totals']['frequency'] = round(($tdfrequency/$d), 0);
				$return['totals']['accepted'] = $tdaccepted;
				$return['totals']['rejected'] = $tdrejected;
				$return['totals']['hw_errors'] = $tdhwerrors;
				$return['totals']['shares'] = $tdshares;
				$return['totals']['hashrate'] = $tdhashrate;
				$return['totals']['last_share'] = max($tdlastshares);
				
			}
		}
		else
		// CG/BFGminer devices stats
		{
			if (isset($stats->devs[0]->DEVS))
			{
				foreach ($stats->devs[0]->DEVS as $device)
				{
					$d++; $c = 0; $tcfrequency = 0; $tcaccepted = 0; $tcrejected = 0; $tchwerrors = 0; $tcshares = 0; $tchashrate = 0; $tclastshares = array();
									
					$name = $device->Name.$device->ID;
					
					$return['devices'][$name]['temperature'] = (isset($device->Temperature)) ? $device->Temperature : false;
					$return['devices'][$name]['frequency'] = (isset($device->Frequency)) ? $device->Frequency : false;
					$return['devices'][$name]['accepted'] = $device->Accepted;
					$return['devices'][$name]['rejected'] = $device->Rejected;
					$return['devices'][$name]['hw_errors'] = $device->{'Hardware Errors'};
					if ($this->_minerdSoftware == "cgdmaxlzeus")
					{
						$return['devices'][$name]['shares'] = ($device->{'Diff1 Work'}) ? round(($device->{'Diff1 Work'}*71582788/1000/1000),0) : 0;	
					}
					else
					{
						$return['devices'][$name]['shares'] = ($device->{'Diff1 Work'}) ? round(($device->{'Diff1 Work'}*71582788/1000),0) : 0;	
					}
					$return['devices'][$name]['hashrate'] = ($device->{'MHS av'}*1000*1000);
					$return['devices'][$name]['last_share'] = $device->{'Last Share Time'};
					$return['devices'][$name]['serial'] = (isset($device->Serial)) ? $device->Serial : false;;

					$tdtemperature += $return['devices'][$name]['temperature'];					
					$tdfrequency += $return['devices'][$name]['frequency'];
					$tdshares += $return['devices'][$name]['shares'];
					$tdhashrate += $return['devices'][$name]['hashrate'];
					
					// Check the real active pool
					$devicePoolIndex[] = $device->{'Last Share Pool'};
				}				
				
				$devicePoolActives = array_count_values($devicePoolIndex);				
			}
			
			if (isset($stats->summary[0]->SUMMARY[0]))
			{
				$totals = $stats->summary[0]->SUMMARY[0];

				$return['totals']['temperature'] = ($tdtemperature) ? round(($tdtemperature/$d), 2) : false;				
				$return['totals']['frequency'] = ($tdfrequency) ? round(($tdfrequency/$d), 0) : false;
				$return['totals']['accepted'] = $totals->Accepted;
				$return['totals']['rejected'] = $totals->Rejected;
				$return['totals']['hw_errors'] = $totals->{'Hardware Errors'};
				$return['totals']['shares'] = $tdshares;
				$return['totals']['hashrate'] = $tdhashrate;
				$return['totals']['last_share'] = $totals->{'Last getwork'};
				
				//log_message("error", var_export($stats->summary[0]->STATUS[0]->Description, true));
				
				if ($this->_minerdSoftware == "cgdmaxlzeus" || 
					$this->_minerdSoftware == "cgminer" || 
					(isset($stats->summary[0]->STATUS[0]->Description) && preg_match("/cgminer/", $stats->summary[0]->STATUS[0]->Description
				))) {
					$cgbfgminerPoolHashrate = round($totals->{'Total MH'} / $totals->Elapsed * 1000000); //round(65536.0 * ($totals->{'Difficulty Accepted'} / $totals->Elapsed), 0); //round(($totals->{'Network Blocks'}*71582788/1000), 0);
				} else {
					$cgbfgminerPoolHashrate = round(($totals->{'Work Utility'}*71582788), 0);
				}
			}
		}
		
		if (isset($stats->pools))
		{
			$return['pool']['hashrate'] = 0;
			$return['pool']['url'] = null;
			$return['pool']['alive'] = 0;

			foreach ($stats->pools as $poolIndex => $pool)
			{
				if ($this->_minerdSoftware == "cpuminer" && !$network)
				{
					if (isset($pool->active) && $pool->active == 1)
					{
						$return['pool']['url'] = $pool->url;
						$return['pool']['alive'] = $pool->alive;

						foreach($pool->stats as $session)
						{
							if ($session->stats_id == $pool->stats_id)
							{
								// Calculate pool hashrate
								$poolHashrate = round(65536.0 * ($session->shares / (time() - $session->start_time)), 0);
								$return['pool']['hashrate'] = $poolHashrate;
							}
						}
					}
				}
				else
				{
					if (isset($pool->active) && $pool->active == 1)
					{
						$return['pool']['url'] = $pool->url;
						$return['pool']['alive'] = $pool->alive;
					}
					$return['pool']['hashrate'] = $cgbfgminerPoolHashrate;

				}
			}
		}
		
		return json_encode($return);
	}

	// Get the stored stats from Redis
	public function getStoredAvgStats()
	{	
		$periods = array("1min" => 60, "5min" => 300, "1hour" => 3600, "1day" => 86400);
		
		foreach ($periods as $period => $seconds)
		{
			if ($seconds == 60)
				$rows = $this->redis->command("ZREVRANGE minerd_delta_stats 0 1");
			else
				$rows = $this->redis->command("ZREVRANGE minerd_avg_stats_$seconds 0 1");
			
			$avgs[$period] = array();			
			if (count($rows) > 0)
			{
				foreach ($rows as $row)	
				{
					$row = json_decode($row);
					$avgs[$period][] = $row;
				}
			}
		}
		
		return $avgs;
	}
	
	// Get the stored stats from Redis
	public function getStoredStats($seconds = 3600, $startTime = false, $avg = false)
	{	
		$current = ($startTime) ? $startTime : time();
		$startTime = $current-$seconds;
		
		if ($avg)
		{
			$o = $this->redis->command("ZRANGEBYSCORE minerd_avg_stats_$avg $startTime $current");
		}
		else
		{
			$o = $this->redis->command("ZRANGEBYSCORE minerd_delta_stats $startTime $current");			
		}

		return $o;
	}
	
	// Get the stored stats from Redis
	public function getHistoryStats($type = "hourly")
	{	
		switch ($type)
		{
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
				$period = 3600*24;
				$range = 7;
				$avg = 3600;
			break;
			case "monthly":
				$period = 3600*24;
				$range = 30;
				$avg = 3600;
			break;
			case "yearly":
				$period = 3600*24*14;
				$range = 27;
				$avg = 86400;
			break;
		}
		
		$items = array();

		for ($i=0;$i<=($range*$period);$i+=$period)
		{
			$statTime = (time()-$i);
			$item = json_decode($this->avgStats($period, $statTime, $avg));
			if ($item)
				$items[] = $item;
		}

		$o = json_encode($items);
		
		return $o;
	}
	
	public function avgStats($seconds = 900, $startTime = false, $avg = false)
	{
		$records = $this->getStoredStats($seconds, $startTime, $avg);
		
		$i = 0; $timestamp = 0; $poolHashrate = 0; $hashrate = 0; $frequency = 0; $accepted = 0; $errors = 0; $rejected = 0; $shares = 0;
		
		if (count($records) > 0)
		{
			foreach ($records as $record)
			{
				$i++;
				$obj = json_decode($record);
				$timestamp += (isset($obj->timestamp)) ? $obj->timestamp : 0;
				$poolHashrate += (isset($obj->pool_hashrate)) ? $obj->pool_hashrate : 0;
				$hashrate += (isset($obj->hashrate)) ? $obj->hashrate : 0;
				$frequency += (isset($obj->avg_freq)) ? $obj->avg_freq : 0;
				$accepted += (isset($obj->accepted)) ? $obj->accepted : 0;
				$errors += (isset($obj->errors)) ? $obj->errors : 0;
				$rejected += (isset($obj->rejected)) ? $obj->rejected : 0;
				$shares += (isset($obj->shares)) ? $obj->shares : 0;
			}
			
			$timestamp = round(($timestamp/$i), 0);
			$poolHashrate = round(($poolHashrate/$i), 0);
			$hashrate = round(($hashrate/$i), 0);
			$frequency = round(($frequency/$i), 0);
			$accepted = round(($accepted/$i), 0);
			$errors = round(($errors/$i), 0);
			$rejected =round(($rejected/$i), 0);
			$shares =round(($shares/$i), 0);
		}

		$o = false;
		if ($timestamp)
		{
			$o = array(
				"timestamp" => $timestamp,
				"seconds" => $seconds,
				"pool_hashrate" => $poolHashrate,
				"hashrate" => $hashrate,
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
	public function storeAvgStats($period = 300, $time = false)
	{
		$now = ($time) ? $time : time();
		
		// Period is in seconds 5m:300 / 1h:3600 / 1d:86400
		$startTime = ($now - $period);
		$stats = $this->avgStats($period, $startTime, false);
		
		// Store average stats for period
		log_message("error", "Stored AVG stats for period ".$period.": ".$stats);
		
		$this->redis->command("ZREM minerd_avg_stats_".$period." false");
		
		if ($stats)
			$this->redis->command("ZADD minerd_avg_stats_".$period. " ".$now." ".$stats);
	}
	
	// Calculate and store the average statistics 5m / 1h / 1d for old delta stats
	public function storeOldAvgStats($period)
	{
		$last = $this->redis->command("ZRANGE minerd_delta_stats 0 0");
		$last = json_decode($last[0]);
		$t = false;
		if (isset($last->timestamp))
			$t = $last->timestamp;

		if ($t)
		{
			for ($i = time(); $i >= $t; $i-=$period)
			{
				$this->storeAvgStats($period, $i);
			}
		}
	}
	
	// Store the live stats on Redis
	public function storeStats()
	{
		log_message('error', "Storing stats...");
		
		$data = new stdClass();
		$stats = $this->getMinerStats();
		
		if ($stats)
		{
			$poolDonationId = false;
			
			// Add pool donation ID to the stats
			if ($stats->pools && count($stats->pools) > 0)
			{
				foreach ($stats->pools as $pool)
				{
					$poolDonationId = ($pool->url == $this->config->item('minera_pool_url') && $pool->user == $this->getMineraPoolUser() && $pool->pass == $this->config->item('minera_pool_password')) ? $pool->priority : false;
				}			
			}
			
			$data = json_decode($this->getParsedStats($stats));
			
			$data->pool_donation_id = $poolDonationId;
	
			$ph = (isset($data->pool->hashrate)) ? $data->pool->hashrate : 0;
			$dh = (isset($data->totals->hashrate)) ? $data->totals->hashrate : 0;
			$fr = (isset($data->totals->frequency)) ? $data->totals->frequency : 0;
			$ac = (isset($data->totals->accepted)) ? $data->totals->accepted : 0;
			$hw = (isset($data->totals->hw_errors)) ? $data->totals->hw_errors : 0;
			$re = (isset($data->totals->rejected)) ? $data->totals->rejected : 0;
			$sh = (isset($data->totals->shares)) ? $data->totals->shares : 0;
			$ls = (isset($data->totals->last_share)) ? $data->totals->last_share : 0;
									
			// Get totals
			$o = array(
				"timestamp" => time(),
				"pool_hashrate" => $ph,
				"hashrate" => $dh,
				"avg_freq" => $fr,
				"accepted" => $ac,
				"errors" => $hw,
				"rejected" => $re,
				"shares" => $sh,
				"last_share" => $ls,
			);
	
			// Get latest
			$latest = $this->redis->command("ZREVRANGE minerd_totals_stats 0 0");
			$lf = 0; $la = 0; $le = 0; $lr = 0; $ls = 0;
			if ($latest)
			{
				$latest = json_decode($latest[0]);
				$lfr = $latest->avg_freq;
				$lac = $latest->accepted;
				$lhw = $latest->errors;
				$lre = $latest->rejected;
				$lsh = $latest->shares;
				$lls = (isset($latest->last_share)) ? $latest->last_share: 0;
			}
	
			// Get delta current-latest
			$delta = array(
				"timestamp" => time(),
				"pool_hashrate" => $ph,
				"hashrate" => $dh,
				"avg_freq" => max((int)($fr - $lfr), 0),
				"accepted" => max((int)($ac - $lac), 0),
				"errors" => max((int)($hw - $lhw), 0),
				"rejected" => max((int)($re - $lre), 0),
				"shares" => max((int)($sh - $lsh), 0),
				"last_share" => $lls,
			);
			
			// Store delta
			$this->redis->command("ZADD minerd_delta_stats ".time()." ".json_encode($delta));			
			
			log_message('error', "Delta Stats stored as: ".json_encode($delta));
			
			// Store totals
			$this->redis->command("ZADD minerd_totals_stats ".time()." ".json_encode($o));
			
			log_message('error', "Total Stats stored as: ".json_encode($o));
		}
		
		return $data;
		
	}
	
	function getStoredDonations()
	{
		return $this->redis->command("LRANGE saved_donations 0 -1");
	}
	
	function getMineraPoolUser()
	{
		$mineraSystemId = $this->generateMineraId();
		
		return $this->config->item('minera_pool_username')."-".$this->redis->get("minera_system_id");
	}
	
	function autoAddMineraPool()
	{
		$pools = json_decode($this->getPools());

		foreach ($pools as $pool)
		{
			$md5s[] = md5(strtolower($pool->url).strtolower($pool->username).strtolower($pool->password));
		}

		$mineraMd5 = md5($this->config->item('minera_pool_url').$this->getMineraPoolUser().$this->config->item('minera_pool_password'));
		$mineraSHA256Md5 = md5($this->config->item('minera_pool_url_sha256').$this->getMineraPoolUser().$this->config->item('minera_pool_password'));
		
		$algo = $this->checkAlgo(false);
		
		$keysSha = array(); $keysScrypt = array();
		
		$keysSha = array_keys($md5s, $mineraSHA256Md5);		
		$keysScrypt = array_keys($md5s, $mineraMd5);

		foreach ($keysScrypt as $vScrypt)
		{
			unset($pools[$vScrypt]);
			unset($md5s[$vScrypt]);
		}
		
		foreach ($keysSha as $vSha)
		{
			unset($pools[$vSha]);
			unset($md5s[$vSha]);
		}
		
		$pools = array_values($pools);
			
		if ($algo === "Scrypt" && !in_array($mineraMd5, $md5s))
		{
			array_push($pools, array("url" => $this->config->item('minera_pool_url'), "username" => $this->getMineraPoolUser(), "password" => $this->config->item('minera_pool_password')) );
	
			$this->setPools($pools);
		}
		elseif ($algo === "SHA-256" && !in_array($mineraSHA256Md5, $md5s))
		{
			array_push($pools, array("url" => $this->config->item('minera_pool_url_sha256'), "username" => $this->getMineraPoolUser(), "password" => $this->config->item('minera_pool_password')) );
			
			$this->setPools($pools);
		}
		
		$newPools = $this->getPools();
		$conf = json_decode($this->redis->get("minerd_json_settings"));
		$conf->pools = $this->parsePools($this->redis->get("minerd_software"), json_decode($newPools, true));

		$jsonConfRedis = json_encode($conf);
		$jsonConfFile = json_encode($conf, JSON_PRETTY_PRINT);
		
		//log_message("error", var_export($conf, true));
		// Save the JSON conf file
		file_put_contents($this->config->item("minerd_conf_file"), $jsonConfFile);
		$this->redis->set("minerd_json_settings", $jsonConfRedis);
	}
	
	function removeOldMineraPool() 
	{
		$pools = json_decode($this->getPools());
		$newPools = array();
		
		foreach ($pools as $pool)
		{
			if ($pool->username !== 'michelem.minera') {
				$newPools[] = $pool;
			} else {
				$pool->username = $this->getMineraPoolUser();
				$newPools[] = $pool;
			}
		}
		
		$this->setPools($newPools);
		
		$conf = json_decode($this->redis->get("minerd_json_settings"));
		$conf->pools = $this->parsePools($this->redis->get("minerd_software"), json_decode(json_encode($newPools), true));

		$jsonConfRedis = json_encode($conf);
		$jsonConfFile = json_encode($conf, JSON_PRETTY_PRINT);

		// Save the JSON conf file
		file_put_contents($this->config->item("minerd_conf_file"), $jsonConfFile);
		$this->redis->set("minerd_json_settings", $jsonConfRedis);
	}
	
	function setPools($pools)
	{
		return $this->redis->set("minerd_pools", json_encode($pools));
	}

	function getPools()
	{
		return $this->redis->get("minerd_pools");
	}
	
	function parsePools($minerSoftware, $pools)
	{
		$poolsArray = array();
		
		if (is_array($pools)) {
			foreach ($pools as $pool)
			{
				if ($minerSoftware == "cgminer" OR $minerSoftware == "cgdmaxlzeus")
				{
					// CGminer has different method to add proxy pool
					if (!empty($pool['proxy']))
					{
						$poolsArray[] = array("url" => $pool['proxy']."|".$pool['url'], "user" => $pool['username'], "pass" => $pool['password']);	
					}
					else
					{
						$poolsArray[] = array("url" => $pool['url'], "user" => $pool['username'], "pass" => $pool['password']);
					}
				}
				else
				{
					if (!empty($pool['proxy']))
					{
						$poolsArray[] = array("url" => $pool['url'], "user" => $pool['username'], "pass" => $pool['password'], "pool-proxy" => $pool['proxy']);	
					}
					else
					{
						$poolsArray[] = array("url" => $pool['url'], "user" => $pool['username'], "pass" => $pool['password']);
					}		
				}
			}
		}
		
		return  $poolsArray;
	}
	
	function setCommandline($string)
	{
		return $this->redis->set("minerd_settings", $string);
	}

	function getCommandline()
	{
		return $this->redis->get("minerd_settings");
	}
	
	/*
	//
	// Clone/export/save/share configs
	//
	*/
	
	public function importFile($post)
	{
		$config['upload_path'] = '/tmp/';
		$config['allowed_types'] = 'json|txt';
		$config['overwrite'] = true;

		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload('import_system_config'))
		{
			$data = array('error' => $this->upload->display_errors());
		}
		else
		{
			$data = $this->upload->data();
			if (file_exists($data['full_path']))
			{
				$json = file_get_contents($data['full_path']);
				if ($this->isJson($json))
				{
					$data = json_decode($json);
					$this->redis->set("import_data_tmp", $json);
				}
				else
				{
					$data = array('error' => "File is not JSON valid");
				}
			}
		}
			
		return $data;
	}
	
	public function cloneSystem()
	{
		$data = $this->redis->get("import_data_tmp");
		if ( $this->isJson($data))
		{
			foreach (json_decode($data) as $key => $value)
			{
				$this->redis->set($key, $value);
			}
			
			$this->session->set_flashdata('message', '<b>Success!</b> System cloned!');
			$this->session->set_flashdata('message_type', 'success');

		}

		log_message("error", "Cloning the system with this data: ".$data);

		$this->redis->del("import_data_tmp");
		
			
		return true;
	}
	
	function deleteSavedConfig($id)
	{
		return $this->redis->command("HDEL saved_miner_configs ".$id);
	}

	function loadSavedConfig($id)
	{
		$encoded = $this->redis->command("HGET saved_miner_configs ".$id);

		if ($encoded)
		{
			$obj = json_decode(base64_decode($encoded));

			if (is_object($obj))
			{
				$settings = $obj->settings;
				$this->redis->set("manual_options", 1);
				$this->redis->set("guided_options", false);
				$this->redis->set("minerd_software", $obj->software);
				$this->redis->set("minerd_manual_settings", $settings);
				$settings .= " -c ".$this->config->item("minerd_conf_file");
				$this->setCommandline($settings);
				$this->setPools($obj->pools);
				
				$poolsArray = array();
				foreach ($obj->pools as $pool)
				{
					$poolsArray[] = array("url" => $pool->url, "user" => $pool->username, "pass" => $pool->password);
				}
				$confArray['pools'] = $poolsArray;
			
				// Prepare JSON conf
				$jsonConfRedis = json_encode($confArray);
				$jsonConfFile = json_encode($confArray, JSON_PRETTY_PRINT);
			
				// Save the JSON conf file
				file_put_contents($this->config->item("minerd_conf_file"), $jsonConfFile);
				$this->redis->set("minerd_json_settings", $jsonConfRedis);
				
				// Startup script rc.local
				$this->saveStartupScript($obj->software);
				
				$this->session->set_flashdata('message', '<b>Success!</b> Miner config loaded!');
				$this->session->set_flashdata('message_type', 'success');
			}
		}
	}
	
	function shareSavedConfig($post)
	{
		$encoded = $this->redis->command("HGET saved_miner_configs ".$post['config_id']);

		$data = array("error" => true);
		
		if ($encoded)
		{
			$obj = json_decode(base64_decode($encoded));

			if (is_object($obj))
			{
				$data = array('timestamp' => $obj->timestamp, 'description' => $post['config_description'], 'miner' => $obj->software, 'settings' => $obj->settings);
				
				$result = $this->useCurl($this->config->item('minera_share_configs_url'), false, "POST", json_encode($data));
				
				log_message("error", "Config sent to Minera: ".json_encode($data));
		
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
		
		$profit = @file_get_contents('http://getminera.com/api/profit', 0, $ctx);
		
		return $profit;
	}
	
	public function getAvgProfitability()
	{
		$profits = json_decode($this->redis->get('coins_profitability'));
		$i = 1; $sum = 0; $ltc = 0;
		
		if (count($profits) > 0) {
		    foreach($profits as $k => $v )
		    {
			    if (isset($v->btc_profitability) && $v->symbol === "ltc")
			        $ltc = $v->btc_profitability;
		    }
    
			foreach ($profits as $profit) {
				if (isset($v->btc_profitability) && $profit->symbol !== "btc" && $profit->symbol !== "ltc" && $profit->btc_profitability >= $ltc) {
					$sum += $profit->btc_profitability;
					$i++;
				}
			}
		}
		
		$o = (($sum+$ltc)/$i);
		
		return ($o > 0) ? number_format($o, 8) : false;
	}
	
	// Get Bitstamp API to look at BTC/USD rates
	public function getBtcUsdRates()
	{
		// wait 1d before recheck
		if (time() > ($this->redis->get("bitstamp_update")+600))
		{
			log_message('error', "Refreshing Bitstamp data");
			
			$object = false;
			
			if ($json = @file_get_contents("https://www.bitstamp.net/api/ticker/"))
			{
				if ($jsonConv = @file_get_contents('https://www.bitstamp.net/api/eur_usd/'))
				{
					$conv = json_decode($jsonConv);
					$exchangeEur = ($conv->sell+$conv->buy)/2;
					$a = json_decode($json);
					$o = array(
						"high_eur" => round(($a->high/$exchangeEur), 2),
						"last_eur" => round(($a->last/$exchangeEur), 2),
						"high" => $a->high,
						"last" => $a->last,
						"timestamp" => $a->timestamp,
						"bid_eur" => round(($a->bid/$exchangeEur), 2),
						"vwap_eur" => round(($a->vwap/$exchangeEur), 2),
						"bid" => $a->bid,
						"vwap" => $a->vwap,
						"volume" => $a->volume,
						"low_eur" => round(($a->low/$exchangeEur), 2),
						"ask_eur" => round(($a->ask/$exchangeEur), 2),
						"low" => $a->low,
						"ask" => $a->ask,
						"eur_usd" => $exchangeEur
					);
	
					$object = json_decode(json_encode($o), FALSE);
				}
			}
			
			if ($object)
			{
				$this->redis->set("bitstamp_update", time());
			
				$this->redis->set("bitstamp_data", json_encode($object));
				
				return $object;
			}
		} 
		else 
		{
			return json_decode($this->redis->get("bitstamp_data"));
		}
		
	}
	
	// Get Cryptsy API to look at BTC rates
	public function getCryptsyRates($id)
	{
		$ctx = stream_context_create(array('http' => array('timeout' => 3)));
		
		if ($json = @file_get_contents("https://www.cryptsy.com/api/v2/markets/$id", 0, $ctx))
		{
			$a = json_decode($json);
			$o = false;
			if ($a->success)
			{
				log_message("error", var_export($a->data->label, true));
				$o[$id] = array(
					"primaryname" => $a->data->label, 
					"secondaryname" => $a->data->label, 
					"primarycode" => $a->data->{'24hr'}->volume, 
					"secondarycode" => $a->data->{'24hr'}->volume_btc, 
					"label" => $a->data->label, 
					"price" => $a->data->last_trade->price,
					"time" => $a->data->last_trade->timestamp
				);
			}
			return $o;
		}
		else
		{
			return false;
		}
	}

	// Get Cryptsy API to look at currency IDs/Values
	public function getCryptsyRateIds()
	{
		if ($json = @file_get_contents("https://www.cryptsy.com/api/v2/markets"))
		{
			$a = json_decode($json);
			
			if ($a->success)
			{
				$o = array();

				foreach ($a->data as $market)
				{
					if (preg_match("/\/BTC$/", $market->label))
					{
						$o[$market->id] = array("codes" => $market->label, "names" => $market->label);
					}
				}
			}
			
			return json_encode($o);
		}
		else
		{
			return false;
		}
	}
	
	// Refresh Cryptsy data IDs/Values
	public function refreshCryptsyData()
	{
		// wait 1d before recheck
		if (time() > ($this->redis->get("cryptsy_update")+86400*7))
		{
			if ($this->redis->get("cryptsy_data_lock"))
				return true;
				
			log_message('error', "Refreshing Cryptsy data");
			
			$this->redis->set("cryptsy_data_lock", true);
			
			$data = $this->getCryptsyRateIds();
			
			if ($data)
			{
				$this->redis->set("cryptsy_update", time());
			
				$this->redis->set("cryptsy_data", $data);
			}
			
			$this->redis->del("cryptsy_data_lock");
		}
	}

	// Refresh Cryptsy data IDs/Values
	public function updateAltcoinsRates($force = false)
	{
		$oldData = ($this->redis->get("altcoins_data")) ? $this->redis->get("altcoins_data") : array("error" => "true");
		
		// wait 1d before recheck
		if (time() > ($this->redis->get("altcoins_update")+3600) || $force)
		{
			if ($this->redis->get("altcoins_data_lock"))
				return $oldData;

			$this->redis->set("altcoins_data_lock", true);
			
			log_message('error', "Refreshing Altcoins rates data");
			
   			$o = false;
			if ($this->redis->get("dashboard_coin_rates"))
    		{
    			$altcoins = json_decode($this->redis->get("dashboard_coin_rates"));
    			if (is_array($altcoins))
    			{
    				foreach ($altcoins as $altcoin) {
    					$altcoinRate = $this->getCryptsyRates($altcoin);
    					if ($altcoinRate)
    					{
    						$o[$altcoin] = $altcoinRate;
    					}
    				}
    		
					$this->redis->set("altcoins_update", time());

					$this->redis->set("altcoins_data", json_encode($o));
    			}
    		}
    		
			$this->redis->del("altcoins_data_lock");
			return $o;
		}
		else
		{
			return json_decode($oldData);
		}
	}
	
	// Get Cryptsy altdata saved
	public function getAltcoinsRates()
	{
		return ($this->redis->get("altcoins_data")) ? json_decode($this->redis->get("altcoins_data")) : array("error" => "true");
	}
		
	/*
	//
	// Miner and System related stuff
	//
	*/
		
	// Check if pool is alive
	public function checkPool($url)
	{	
		$parsedUrl = @parse_url($url);

		if (isset($parsedUrl['host']) && isset($parsedUrl['port']))
		{
			$conn = @fsockopen($parsedUrl['host'], $parsedUrl['port'], $errno, $errstr, 1);
			if (is_resource($conn))
			{
				fclose($conn);
				return true;
			}
		}		
		
		return false;
	}
	
	public function getNetworkMiners()
	{
		return json_decode($this->redis->get('network_miners'));
	}

	// Check if the minerd if running
	public function isOnline($network = false)
	{
		$ip = "127.0.0.1"; $port = 4028;
		
		if ($network) list($ip, $port) = explode(":", $network);	

		if(!($fp = @fsockopen($ip, $port, $errno, $errstr, 1)))
		{
				return false;
		}		
		
		if (is_resource($fp)) fclose($fp);
		
		return true;
	}
	
	// Check RPi temp
	public function checkTemp()
	{
		if (file_exists($this->config->item("rpi_temp_file")))
		{
			$scale = ($this->redis->get("dashboard_temp")) ? $this->redis->get("dashboard_temp") : "c";
			$temp = number_format( ( (int)exec("cat ".$this->config->item("rpi_temp_file"))/1000), 2 );

			if ($scale == "f")
				$temp = intval((9/5)* $temp + 32);

			return array("value" => $temp, "scale" => $scale);
		}
		else
		{
			return false;
		}
	}
	
	public function checkMinerIsUp()
	{		
		// Check if miner is not manually stopped
		if ($this->redis->get("minerd_status"))
		{
			if ($this->isOnline() === false)
			{
				log_message('error', "It seems miner is down, trying to restart it");
				// Force stop and killall
				$this->minerStop();
				// Restart miner
				$this->minerStart();
			}
			
			log_message('error', "Miner is up");
		}
		
		return;
	}
	
	public function readCustomMinerDir()
	{
		$files = array();
		$newActiveCustomMiners = array();

		$activeCustomMiners = (json_decode($this->redis->get('active_custom_miners'))) ? json_decode($this->redis->get('active_custom_miners')) : array();

		if ($handle = opendir(FCPATH.'minera-bin/custom/')) {
		    while (false !== ($entry = readdir($handle))) {
		        if ($entry != "." && $entry != ".." && $entry != "README.custom")
		        {
			        $files[] = $entry;
		        }
		    }
		
		    closedir($handle);
		    
		    foreach ($activeCustomMiners as $activeCustomMiner) {
		       	// Remove active ones from redis if someone has removed the file by hand
			   	if (in_array($activeCustomMiner, $files))
			   	{
				   	$newActiveCustomMiners[] = $activeCustomMiner;
			   	}
		    }
		    
		    $this->redis->set('active_custom_miners', json_encode($newActiveCustomMiners));
		}
		
		return $files;
	}
	
	public function deleteCustomMinerFile($file)
	{
		$r = shell_exec("sudo rm ".FCPATH.'minera-bin/custom/'.str_replace(" ", "\ ", $file));
		return array('success' => $r);
	}
	
	// Refresh miner confs
	public function refreshMinerConf()
	{
		// wait 1w before recheck
		if (file_exists(FCPATH."miners_conf.json") && time() > ($this->redis->get("miners_conf_update")+86400*7))
		{
			log_message('error', "Refreshing Miners conf data");
			
			$data = json_decode(file_get_contents(FCPATH."miners_conf.json"), true);
			
			if ($data)
			{
				$this->redis->set("miners_conf_update", time());
			
				$this->redis->set("miners_conf", json_encode($data));
			}
		}
		
		return $this->redis->get("miners_conf");
	}
	
	public function is_valid_domain_name($domain_name)
	{
	    return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name) //valid chars check
            && preg_match("/^.{1,253}$/", $domain_name) //overall length check
            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name)   ); //length of each label
	}
	
	public function setSystemHostname($hostname) {
		if($this->is_valid_domain_name($hostname))
		{
			exec("sudo hostname " . $hostname);
			exec("echo ".$hostname." | sudo tee /etc/hostname");
			exec("echo 127.0.0.1     ".$hostname." | sudo tee --append /etc/hosts");
		    return true;
		} 
		else 
		{
			return false;	
		}
	}
	
	public function setSystemUserPassword($password) {
		exec("echo 'minera:".$password."' | sudo -S /usr/sbin/chpasswd");
		return true;
	}
	
	// Call shutdown cmd
	public function shutdown()
	{
		log_message('error', "Shutdown cmd called");
		
		$this->minerStop();
		$this->redis->del("cron_lock");
		$this->redis->command("BGSAVE");
		sleep(2);
		
		exec("sudo shutdown -h now");

		return true;
	}

	// Call reboot cmd
	public function reboot()
	{
		log_message('error', "Reboot cmd called");

		$this->minerStop();
		$this->redis->del("cron_lock");
		$this->redis->command("BGSAVE");
		sleep(2);
		
		exec("sudo reboot");

		return true;
	}
	
	// Write rc.local startup file
	public function saveStartupScript($minerSoftware, $delay = 5, $extracommands = false)
	{
		$this->switchMinerSoftware($minerSoftware);
		
		$command = array($this->config->item("screen_command"), $this->config->item("minerd_command"), $this->getCommandline());
		
		$rcLocal = file_get_contents(FCPATH."rc.local.minera");
		
		$rcLocal .= "\nredis-cli set minerd_running_software $minerSoftware\nsleep $delay\nsu - ".$this->config->item('system_user').' -c "'.implode(' ', $command)."\"\n$extracommands\nexit 0";
		
		file_put_contents('/etc/rc.local', $rcLocal);
		
		log_message('error', "Startup script saved: ".var_export($rcLocal, true));

		return true;
	}
	
	public function tailFile($filename, $lines) {
		$file = file(FCPATH.APPPATH."logs/".$filename);
		
		if (count($file) > 0) {
			for ($i = count($file)-$lines; $i < count($file); $i++) {
				if ($i >= 0 && $file[$i]) {
					$readlines[] = $file[$i] . "\n";
				}
			}
		} else {
			$readlines = array('No logs found');
		}
		
		return $readlines;
	}

	public function saveCurrentFreq()
	{
		return $this->miner->saveCurrentFreq();
	}

	public function selectPool($poolId, $network = false)
	{
		if ($network) {
			$this->switchMinerSoftware(false, true);
			return $this->network_miner->selectPool($poolId, $network);
		}
			
		return $this->miner->selectPool($poolId, $network);
	}
	
	public function addPool($url, $user, $pass, $network = false)
	{
		if ($network) {
			$this->switchMinerSoftware(false, true);
			return $this->network_miner->addPool($url, $user, $pass, $network);
		}
		
		return $this->miner->addPool($url, $user, $pass, $network);
	}
	
	public function removePool($poolId, $network = false)
	{
		if ($network) {
			$this->switchMinerSoftware(false, true);
			return $this->network_miner->removePool($poolId, $network);
		}
					
		return $this->miner->removePool($poolId, $network);
	}
			
	// Stop miner
	public function minerStop()
	{
		// Check if there is a running miner and 
		// stop that before start another one
		$software = $this->redis->get("minerd_running_software");
		if ($software)
		{
			log_message("error", "Stopping running software: ".$software);
			$this->switchMinerSoftware($software);
		}
		else
		{
			$this->switchMinerSoftware();
		}
		
		$minerdUser = ($this->redis->get("minerd_running_user")) ? $this->redis->get("minerd_running_user") : $this->config->item("system_user");

		exec("sudo -u " . $minerdUser . " " . $this->config->item("screen_command_stop"));
		exec("sudo -u " . $minerdUser . " /usr/bin/killall -s9 ".$this->config->item("minerd_binary"));
		
		$this->redis->del("latest_stats");
		$this->redis->set("minerd_status", false);
		$this->redis->set("minerd_running_software", false);
		
		log_message('error', $this->_minerdSoftware." stopped");
		
		$this->redis->command("BGSAVE");
					
		return true;
	}
	
	// Start miner
	public function minerStart()
	{
		$this->resetCounters();
		$this->checkCronIsRunning();
		$software = $this->redis->get("minerd_software");

		$this->redis->set("minerd_status", true);
		$this->redis->set("minerd_running_software", $software);

		$this->switchMinerSoftware();
		
		$this->redis->set("minerd_running_user", $this->config->item("system_user"));
		
		// If it's cgminer with logging we need to create a script and give that to screen
		$specialLog = null;
		if ($this->config->item("minerd_special_log") && $this->redis->get("minerd_log"))
		{
			if (file_exists(FCPATH."minera-bin/cgminerStartupScript"))
			{
				shell_exec("sudo -u " . $this->config->item("system_user") . " sudo chmod 777 " . FCPATH."minera-bin/cgminerStartupScript");
			}
		
			$script = "#!/bin/bash\n\n".$this->config->item("minerd_command")." ".$this->getCommandline()." 2>".$this->config->item("minerd_log_file");
			
			file_put_contents(FCPATH."minera-bin/cgminerStartupScript", $script);
			
			$command = array($this->config->item("screen_command"), FCPATH."minera-bin/cgminerStartupScript");
		}
		else
		{
			$command = array($this->config->item("screen_command"), $this->config->item("minerd_command"), $this->getCommandline());
		}
		
		$finalCommand = "sudo -u " . $this->config->item("system_user") . " " . implode(" ", $command);
		
		exec($finalCommand, $out);
		
		log_message('error', "Minerd started with command: $finalCommand - Output was: ".var_export($out, true));
		
		sleep(9);
		
		if (file_exists($this->config->item('minerd_log_file')))
		{
			shell_exec("sudo -u " . $this->config->item("system_user") . " sudo chmod 666 " . $this->config->item('minerd_log_file'));
		}
		
		if ($this->isEnableMobileminer())
		{
			$pools = json_decode($this->getPools());
			if (count($pools) > 0)
			{
				foreach ($pools as $key => $pool)
				{
					$mmPools[] = $key."||".$pool->username."@".$pool->url;
				}
				
				$params = array("emailAddress" => $this->redis->get("mobileminer_email"), "applicationKey" => $this->redis->get("mobileminer_appkey"), "apiKey" => $this->config->item('mobileminer_apikey'), "machineName" => $this->redis->get("mobileminer_system_name"));
				
				$this->useCurl($this->config->item('mobileminer_url_poolsinput'), $params, "POST", json_encode($mmPools));
				
				log_message("error", "Sent MobileMiner pools: ".json_encode($mmPools));
			}			
		}
		
		$this->redis->command("BGSAVE");
		
		return true;
	}
	
	// Restart minerd
	public function minerRestart()
	{
		$this->resetCounters();
	
		$this->minerStop();
		sleep(1);
	
		$this->minerStart();
		sleep(1);
		
		return true;
	}

	// Save a fake last data to get correct next delta
	public function resetCounters()
	{
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
		$this->redis->command("ZADD minerd_totals_stats ".time()." ".json_encode($reset));
	}
	
	// Check if cron is running and force the deletion of lock if not.
	public function checkCronIsRunning()
	{
		$o = shell_exec("ps aux |grep 'index.php app cron'");

		if (preg_match("/\/bin\/sh -c php/", $o))
		{
			log_message("error", "Cron running...");
			return true;
		}			
		else
		{
			log_message("error", "Cron NOT running. Deleting lock.");
			$this->redis->del("cron_lock");
			return false;	
		}
	}
		
	// Call update cmd
	public function update()
	{
		$lines = array();
		// Pull the latest code from github
		exec("cd ".FCPATH." && sudo -u " . $this->config->item("system_user") . " sudo git fetch --all && sudo git reset --hard origin/master", $out);
		
		$logmsg = "Update request from ".$this->currentVersion()." to ".$this->redis->command("HGET minera_update new_version")." : ".var_export($out, true);
		
		$lines[] = $logmsg;
		
		log_message('error', $logmsg);

		$this->redis->del("altcoins_update");		
		$this->util_model->updateAltcoinsRates(true);
		$this->redis->del("minera_update");
		$this->redis->del("minera_version");
		$this->checkUpdate();
				
		// Run upgrade script
		exec("cd ".FCPATH." && sudo -u " . $this->config->item("system_user") . " sudo ./upgrade_minera.sh", $out);

		$logmsg = "Running upgrade script".var_export($out, true);

		$lines[] = $logmsg;
				
		log_message('error', $logmsg);
		
		$logmsg = "End Update";
		$lines[] = $logmsg;
		log_message('error', $logmsg);
		
		return json_encode($lines);
	}
	
	// Reset Minera data
	public function reset($action)
	{
		switch($action)
		{					
		    case "charts":
				$this->redis->del("minerd_totals_stats");
				$this->redis->del("minerd_delta_stats");
				$this->redis->del("minerd_stats");
				$this->redis->del("minerd_avg_stats_86400");
				$this->redis->del("minerd_avg_stats_3600");
				$this->redis->del("minerd_avg_stats_300");
				$o = json_encode(array("success" => true));
		    break;
			case "options":
				$this->redis->set("guided_options", 1);
				$this->redis->set("manual_options", 0);
				$o = json_encode(array("success" => true));
		    break;
		    default:
		    	$o = json_encode(array("err" => true));
		}
		
		return $o;
	}
	
	public function factoryReset() 
	{
		$this->minerStop();
		sleep(3);
		
		// SET
		$this->redis->set("minerd_autorestart", 0);
		$this->redis->set("minerd_delaytime", 5);
		$this->redis->set("scheduled_event_time", "");
		$this->redis->set("minera_donation_time", 0);
		$this->redis->set("minerd_autorestart_time", 600);
		$this->redis->set("minerd_manual_settings", "");
		$this->redis->set("minerd_extraoptions", "");
		$this->redis->set("minerd_use_root", 0);
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
		$this->redis->set("minerd_software", "cpuminer");
		$this->redis->set("manual_options", 0);
		$this->redis->set("minerd_autorecover", 0);
		$this->redis->set("scheduled_event_action", "");
		$this->redis->set("anonymous_stats", 1);
		$this->redis->set("dashboard_skin", "black");
		$this->redis->set("guided_options", 1);
		$this->redis->set("mobileminer_enabled", 0);
		$this->redis->set("minerd_autorestart_devices", 0);
		$this->redis->set("minerd_log", 0);
		$this->redis->set("minerd_status", 0);
		$this->redis->set("minerd_scrypt", 0);
		$this->redis->set("dashboard_refresh_time", 300);
		$this->redis->set("donation_time_remain", 1);
		$this->redis->set("scheduled_event_start_time", "");
		$this->redis->set("minera_password", "70e880b1effe0f770849d985231aed2784e11b38");
		$this->redis->set("dashboard_coin_rates", json_encode(array()));
		$this->redis->set("system_extracommands", "");
		$this->redis->set("minerd_append_conf", 1);
		$this->redis->set("minerd_running_user", "minera");
		$this->redis->set("minerd_debug", 0);
		$this->redis->set("pool_global_proxy", "");
		$this->redis->set("minerd_pools", "");
		$this->redis->set("minerd_autodetect", 0);
		$this->redis->set("minerd_api_allow_extra", "");
		
		// DEL
		$this->redis->del("minera_version");
		$this->redis->del("active_custom_miners");
		$this->redis->del("minera_update");
		$this->redis->del("cryptsy_update");
		$this->redis->del("latest_stats");
		$this->redis->del("minerd_avg_stats_86400");
		$this->redis->del("minerd_avg_stats_3600");
		$this->redis->del("minerd_avg_stats_300");
		$this->redis->del("saved_miner_configs");
		$this->redis->del("cryptsy_data");
		$this->redis->del("bitstamp_data");
		$this->redis->del("saved_donations");
		$this->redis->del("minera_remote_config");
		$this->redis->del("export_settings");
		$this->redis->del("altcoins_data");
		$this->redis->del("network_miners");
		$this->redis->del("minerd_totals_stats");
		$this->redis->del("miners_conf");
		$this->redis->del("miners_conf_update");
		$this->redis->del("minerd_delta_stats");
		$this->redis->del("saved_miner_config:*");
		$this->redis->del("minerd_json_settings");
		$this->redis->del("import_data_tmp");
		$this->redis->del("bitstamp_update");
		$this->redis->del("altcoins_update");
		
		// Add donation pool
		$this->autoAddMineraPool();
		
		return true;		
	}
	
	// Check Minera version
	public function checkUpdate()
	{
		// wait 1h before recheck
		if (time() > ($this->redis->command("HGET minera_update timestamp")+3600))
		{
			log_message('error', "Checking Minera updates");

			$latestConfig = $this->getRemoteJsonConfig();
			$localVersion = $this->currentVersion();
			
			if (isset($latestConfig->version)) {
				$this->redis->command("HSET minera_update timestamp ".time());
				$this->redis->command("HSET minera_update new_version ".$latestConfig->version);
	
				if ($latestConfig->version != $localVersion)
				{
					log_message('error', "Found a new Minera update");
	
					$this->redis->command("HSET minera_update value 1");
					return true;
				}
			
				$this->redis->command("HSET minera_update value 0");
			}
		}
		else
		{
			if ($this->redis->command("HGET minera_update value"))
				return true;
			else
				return false;
		}
	}

	// Get local Minera version
	public function currentVersion($cron = false)
	{
		// wait 1h before recheck
		if (time() > ((int)$this->redis->command("HGET minera_version timestamp")+3600) && $cron == false)
		{
			$this->redis->command("HSET minera_version timestamp ".time());
			$localConfig = json_decode(file_get_contents(base_url('minera.json')));
			$this->redis->command("HSET minera_version value ".$localConfig->version);
			return $localConfig->version;
		}
		else
		{
			return $this->redis->command("HGET minera_version value");
		}
	}
	
	// Generate a uniq hash ID for Minera System ID
	public function generateMineraId()
	{
		$mac = shell_exec('sudo cat /sys/class/net/eth0/address');
		$id = substr(strtolower(preg_replace('/[0-9_\/]+/','',base64_encode(sha1(trim($mac))))),0,12);
		$this->redis->set("minera_system_id", $id);
		return $id;
	}
	
	// Get Remote configuration from Github 
	public function getRemoteJsonConfig()
	{
		$remoteConfig = @file_get_contents($this->config->item('remote_config_url'));

		$this->redis->set("minera_remote_config", $remoteConfig);

		return json_decode($remoteConfig);
	}
	
	// Return saved remote configuration from local Redis 
	public function returnRemoteJsonConfig()
	{
		return json_decode($this->redis->get("minera_remote_config"));
	}
	
	// Send anonymous stats to Minera main system
	public function sendAnonymousStats($id, $stats)
	{
		$params = array("id" => $id);

		log_message("error", "Sending anonymous stats");
		
		$result = $this->useCurl($this->config->item('minera_anonymous_url')."/".$id, false, "POST", json_encode($stats));

		return $result;
	}

	/*
	// Check Mobileminer is enabled
	*/
	public function isEnableMobileminer()
	{
		if ($this->redis->get("mobileminer_enabled"))
		{
			if ($this->redis->get("mobileminer_system_name") && $this->redis->get("mobileminer_email") && $this->redis->get("mobileminer_appkey"))
			{
				return true;
			}
		}
		
		return false;
	}

	/*
	// Check what kind of algo is used to mine
	*/	
	public function checkAlgo($running = true)
	{
		$minerdCommand = $this->getCommandLine();
		$scryptEnabled = $this->redis->get("minerd_scrypt");
		$algo = "SHA-256";

		if ($running)
		{
			if ($scryptEnabled || preg_match("/scrypt/i", $minerdCommand) || $this->redis->get("minerd_running_software") == "cpuminer")
			{
				$algo = "Scrypt";
			}			
		}
		else
		{
			if ($scryptEnabled || preg_match("/scrypt/i", $minerdCommand) || $this->redis->get('minerd_software') == "cpuminer")
			{
				$algo = "Scrypt";
			}	
		}
		
		return $algo;
	}
	
	/*
	// Call the Mobileminer API to send device stats
	*/
	public function callMobileminer()
	{
		if ($this->isEnableMobileminer())
		{
			$stats = json_decode($this->getParsedStats($this->getMinerStats()));
			$networkStats = $this->getNetworkMinerStats(true);
			
			// Params		
			$params = array("emailAddress" => $this->redis->get("mobileminer_email"), "applicationKey" => $this->redis->get("mobileminer_appkey"), "apiKey" => $this->config->item('mobileminer_apikey'), "fetchCommands" => "true");
			
			// Local Pool data
			$poolUrl = (isset($stats->pool->url)) ? $stats->pool->url : "no pool configured";
			$poolStatus = (isset($stats->pool->alive) && $stats->pool->alive) ? "Alive" : "Dead";
			
			// Local Algo data
			$algo = $this->checkAlgo();
			
			// Local data				
			$i = 0; $data = array();
			if (isset($stats->devices) && count($stats->devices) > 0)
			{
				foreach ($stats->devices as $devName => $device)
				{
					$data[] = array(
						"MachineName" => $this->redis->get("mobileminer_system_name"),
						"MinerName" => "Minera",
						"CoinSymbol" => "",
						"CoinName" => "",
						"Algorithm" => $algo,
						"Kind" => "Asic",
						"Name" => $devName,
						"FullName" => $devName,
						"PoolIndex" => 0,
						"PoolName" => $poolUrl,
						"Index" => $i,                                            
						"DeviceID" => $i,
						"Enabled" => $this->isOnline(),
						"Status" => $poolStatus,
						"Temperature" => false,
						"FanSpeed" => 0,
						"FanPercent" => 0,
						"GpuClock" => 0,
						"MemoryClock" => 0,
						"GpuVoltage" => 0,
						"GpuActivity" => 0,
						"PowerTune" => 0,
						"AverageHashrate" => ($device->hashrate > 0) ? round(($device->hashrate/1000), 0) : 0,
						"CurrentHashrate" => ($device->hashrate > 0) ? round(($device->hashrate/1000), 0) : 0,
						"AcceptedShares" => $device->accepted,
						"RejectedShares" => $device->rejected,
						"HardwareErrors" => $device->hw_errors,
						"Utility" => false,
						"Intensity" => null,
						"RejectedSharesPercent" => (($device->accepted+$device->rejected+$device->hw_errors) > 0) ? round(($device->rejected*100/($device->accepted+$device->rejected+$device->hw_errors)), 3) : 0,
						"HardwareErrorsPercent" => (($device->accepted+$device->rejected+$device->hw_errors) > 0) ? round(($device->hw_errors*100/($device->accepted+$device->rejected+$device->hw_errors)), 3) : 0
					);
					$i++;
				}
			}
			
			if (count($networkStats) > 0)
			{
				foreach ($networkStats as $netMinerName => $netMiner)
				{
					// Network Pool data
					$netPoolUrl = (isset($netMiner->pool->url)) ? $netMiner->pool->url : "no pool configured";
					$netPoolStatus = (isset($netMiner->pool->alive) && $netMiner->pool->alive) ? "Alive" : "Dead";
					$netEnabled = (isset($netMiner->devices)) ? true : false;
		
					// Network data							
					$i = 0;
					if (isset($netMiner->devices) && count($netMiner->devices) > 0)
					{
						foreach ($netMiner->devices as $netDevName => $netDevice)
						{
							$data[] = array(
								"MachineName" => $this->redis->get("mobileminer_system_name")." - ".$netMinerName,
								"MinerName" => "Minera",
								"CoinSymbol" => "",
								"CoinName" => "",
								"Algorithm" => $netMiner->config['algo'],
								"Kind" => "Network",
								"Name" => $netDevName,
								"FullName" => $netDevName,
								"PoolIndex" => 0,
								"PoolName" => $netPoolUrl,
								"Index" => $i,                                            
								"DeviceID" => $i,
								"Enabled" => $netEnabled,
								"Status" => $netPoolStatus,
								"Temperature" => (isset($netDevice->temperature)) ? $netDevice->temperature : false,
								"FanSpeed" => 0,
								"FanPercent" => 0,
								"GpuClock" => 0,
								"MemoryClock" => 0,
								"GpuVoltage" => 0,
								"GpuActivity" => 0,
								"PowerTune" => 0,
								"AverageHashrate" => ($netDevice->hashrate > 0) ? round(($netDevice->hashrate/1000), 0) : 0,
								"CurrentHashrate" => ($netDevice->hashrate > 0) ? round(($netDevice->hashrate/1000), 0) : 0,
								"AcceptedShares" => $netDevice->accepted,
								"RejectedShares" => $netDevice->rejected,
								"HardwareErrors" => $netDevice->hw_errors,
								"Utility" => false,
								"Intensity" => null,
								"RejectedSharesPercent" => (($netDevice->accepted+$netDevice->rejected+$netDevice->hw_errors) > 0) ? round(($netDevice->rejected*100/($netDevice->accepted+$netDevice->rejected+$netDevice->hw_errors)), 3) : 0,
								"HardwareErrorsPercent" => (($netDevice->accepted+$netDevice->rejected+$netDevice->hw_errors) > 0) ? round(($netDevice->hw_errors*100/($netDevice->accepted+$netDevice->rejected+$netDevice->hw_errors)), 3) : 0
							);
							$i++;
						}
					}					
				}
			}
			
			//log_message("error", var_export($networkStats, true));
			
			$resultGetActions = false; 
			
			if (count($data) > 0)
			{
				$data_string = json_encode($data);
				
				if (strlen($data_string) > 0)
				{
					// Sending data to Mobile Miner
					log_message('error', "Sending data to Mobileminer");
		
					$resultGetActions = $this->useCurl($this->config->item('mobileminer_url_stats'), $params, "POST", $data_string);								}
			}
						
			/*	
			// Looking for actions to do
			*/
			if ($resultGetActions)
			{				
				$resultGetActions = json_decode($resultGetActions);
				//log_message("error", var_export($resultGetActions, true));
				if (is_array($resultGetActions) && count($resultGetActions) > 0)
				{
					$actionToDo = $resultGetActions[0];
					
					$paramsGetActions = array("emailAddress" => $this->redis->get("mobileminer_email"), "applicationKey" => $this->redis->get("mobileminer_appkey"), "apiKey" => $this->config->item('mobileminer_apikey'), "machineName" => $actionToDo->Machine->Name);

					
					// Do the mobileMiner action
					if ($actionToDo->CommandText == "START")
					{
						if ($actionToDo->Machine->Name === $this->redis->get("mobileminer_system_name") && !$this->isOnline()) $this->minerStart();
					}
					elseif ($actionToDo->CommandText == "STOP")
					{
						if ($actionToDo->Machine->Name === $this->redis->get("mobileminer_system_name")) $this->minerStop();
					}
					elseif ($actionToDo->CommandText == "RESTART")
					{
						if ($actionToDo->Machine->Name === $this->redis->get("mobileminer_system_name")) $this->minerRestart();
					}
					// TODO Add switching for net miners too
					elseif (preg_match("/SWITCH/", $actionToDo->CommandText))
					{
						$switch = explode("|", $actionToDo->CommandText);
						if (isset($switch[1]))
						{
							$array = explode("||", $switch[1]);
							if (isset($array[0]))
							{
								$this->selectPool($array[0]);
							}
						}
					}
					
					log_message('error', 'Action done: '.json_encode($resultGetActions));
					
					/*
					// Remove MobileMiner action by ID
					*/
					$paramsGetActions['commandId'] = $actionToDo->Id;
					
					$this->useCurl($this->config->item('mobileminer_url_remotecommands'), $paramsGetActions, "DELETE");

					log_message('error', 'Removed MobileMiner actions with ID: '.$actionToDo->Id);
					
				}
				else
				{
					log_message('error', 'No MobileMiner actions to do.');						
				}
			}

			return true;
				
		}
		
		return false;
	}
	
	public function checkNetworkDevice($ip, $port=4028) 
	{
		$connection = @fsockopen($ip, 4028, $errno, $errstr, 0.1);
		
	    if (is_resource($connection))
	    {	
	        fclose($connection);
	        
	        return true;
	    }
	    
	    return false;
	}
	
	public function discoveryNetworkDevices() {
		$localIp = $_SERVER['SERVER_ADDR'];

		list($w, $x, $y, $z) = explode('.', $localIp);
				
		$range = implode(".", array($w, $x, $y, '0'))."/24";
		$addresses = array();
		$opens = array();
		
		@list($ip, $len) = explode('/', $range);
		
		if (($min = ip2long($ip)) !== false) {
		  $max = ($min | (1<<(32-$len))-1);
		  for ($i = $min; $i < $max; $i++)
		    $addresses[] = long2ip($i);
		}

		$stored = $this->getNetworkMiners();
		$current = array($localIp);
		
		foreach ($stored as $net) {
			$current[] = $net->ip;
		}
		
		foreach ($addresses as $address)
		{
		    $connection = @fsockopen($address, 4028, $errno, $errstr, 0.01);
		
		    if (is_resource($connection) && !in_array($address, $current))
		    {
		        $opens[] = array('ip' => $address, 'name' => $this->getRandomStarName());
		
		        fclose($connection);
		    }
		}
		
		return $opens;
		
	}
	
	public function getRandomStarName() {
		$array = array("Andromeda", "Antlia", "Apus", "Aquarius", "Aquila", "Ara", "Aries", "Auriga", "Boötes", "Caelum", "Camelopardalis", "Cancer", "Canes Venatici", "Canis Major", "Canis Minor", "Capricornus", "Carina", "Cassiopeia", "Centaurus", "Cepheus", "Cetus", "Chamaeleon", "Circinus", "Columba", "Coma Berenices", "Corona Austrina", "Corona Borealis", "Corvus", "Crater", "Crux", "Cygnus", "Delphinus", "Dorado", "Draco", "Equuleus", "Eridanus", "Fornax", "Gemini", "Grus", "Hercules", "Horologium", "Hydra", "Hydrus", "Indus", "Lacerta", "Leo", "Leo Minor", "Lepus", "Libra", "Lupus", "Lynx", "Lyra", "Mensa", "Microscopium", "Monoceros", "Musca", "Norma", "Octans", "Ophiuchus", "Orion", "Pavo", "Pegasus", "Perseus", "Phoenix", "Pictor", "Pisces", "Piscis Austrinus", "Puppis", "Pyxis", "Reticulum", "Sagitta", "Sagittarius", "Scorpius", "Sculptor", "Scutum", "Serpens", "Sextans", "Taurus", "Telescopium", "Triangulum", "Triangulum Australe", "Tucana", "Ursa Major", "Ursa Minor", "Vela", "Virgo", "Volans", "Vulpecula");
		
		return $array[array_rand($array)];
	}
	
	public function setTimezone($timezone)
	{
		exec("echo '".$timezone."' | sudo tee /etc/timezone && sudo dpkg-reconfigure -f noninteractive tzdata");
		$this->redis->set("minera_timezone", $timezone);
	}
	
	public function convertHashrate($hash)
	{
		if ($hash > 900000000)
			return round($hash/1000000000, 2) . 'Gh/s';
		elseif ($hash > 900000)
			return round($hash/1000000, 2) . 'Mh/s';
		elseif ($hash > 900)
			return round($hash/1000, 2) . 'Kh/s';
		else
			return $hash;
	}
	
	// Check Internet connection
	public function checkConn()
	{
		if(!$fp = fsockopen("www.google.com", 80)) {
			return false;
		}
		
		if (is_resource($fp)) fclose($fp);
		
		return true;
	}
	
	public function getSysUptime()
	{
		return strtok( exec( "cat /proc/uptime" ), "." );
	}

	public function isJson($string) {
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}

	// Socket server to get a fake miner to do tests
	public function fakeMiner()
	{
		$server = stream_socket_server("tcp://127.0.0.1:1337", $errno, $errorMessage);
		
		if ($server === false) {
		    throw new UnexpectedValueException("Could not bind to socket: $errorMessage");
		}
		
		for (;;) {
		    $client = @stream_socket_accept($server);
		
		    if ($client) 
		    {    
		    	// Inject stdin
		        //stream_copy_to_stream($client, $client);
		        
		        // Inject some json
		        $j = file_get_contents('test.json');
		        fwrite($client, $j);
		        
		        fclose($client);
		    }
		}
	}
	
	public function useCurl($url, $params, $method, $post = false)
	{
		if ($params)
			$ch = curl_init($url."?".http_build_query($params));
		else
			$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,3); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		if ($post)
		{
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    	'Content-Type: application/json',
				'Content-Length: ' . strlen($post))
			);
		}
		if ($method == "DELETE")
		{
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		}

		$result = curl_exec($ch);
		
		if(!curl_errno($ch))
		{
			$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			// Not used
		}

		curl_close($ch);
		
		return $result;
	}
}
