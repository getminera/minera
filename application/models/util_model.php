<?php
/*
 * Utit_model
 * Utility model for minera
 *
 * @author michelem
 */
class Util_model extends CI_Model {

	public function __construct()
	{
		// load CPUMiner Model
		// TODO Switch model for CGMiner/BFGMiner
		$this->load->model('cpuminer_model');
		
		parent::__construct();
	}

	/*
	//
	// Stats related stuff
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
	
	// Get the live stats from cpuminer
	public function getStats()
	{
		if ($this->isOnline())
		{
			$a = $this->cpuminer_model->callMinerd();

			if (is_object($a))
			{
				$a = (array)$a;
				
				// Add sysload stats
				$a["sysload"] = sys_getloadavg();
				
				// Add pools
				$pools = json_decode($this->redis->get("minerd_pools"), true);
				
				foreach ($pools as $pool)
				{
					$pool['alive'] = $this->checkPool($pool['url']);
					$nPools[] = $pool; 
				}
				
				$a["pools"] = $nPools;
				
				// Add controller temp
				$a["temp"] = $this->checkTemp();
				
				// Encode and save the latest
				$o = json_encode($a);
				$this->redis->set("latest_stats", $o);
				
				return $o;
			}
			else
			{
				if ($latestSaved = $this->redis->get("latest_stats"))
				{
					return $latestSaved;
				}
				else
				{
					return json_encode(array("error" => true));
				}
			}			
		}
		else
		{
			return json_encode(array("notrunning" => true));
		}
		
		return false;
	}

	// Get the stored stats from Redis
	public function getStoredStats($seconds = 3600)
	{	
		$now = time();
		$onehourago = $now-$seconds;
		$this->load->library('redis');
		$o = $this->redis->command("ZRANGEBYSCORE minerd_stats $onehourago $now");
		
		return $o;
	}
	
	// Store the live stats on Redis
	public function storeStats()
	{
		log_message('error', "Storing stats...");
		
		$data = json_decode($this->getStats());
		
		$totHash = 0; $totFreq = 0; $totAc = 0; $totHw = 0; $totRe = 0; $totSh = 0; $d = 0; $c = 0;

		if ($data && $data->devices && count($data->devices) > 0)
		{
			foreach($data->devices as $device)
			{
				$d++;
				if ($device->chips && count($device->chips) > 0)
				{
					foreach($device->chips as $chip)
					{
						$c++;
						$totHash += $chip->hashrate;
						$totFreq += $chip->frequency;
						$totAc += $chip->accepted;
						$totHw += $chip->hw_errors;
						$totRe += $chip->rejected;
						$totSh += $chip->shares;
					}
				}
			}
		}

		$o = array(
			"timestamp" => time(),
			"hashrate" => $totHash,
			"avg_freq" => round($totFreq/$c),
			"accepted" => $totAc,
			"errors" => $totHw,
			"rejected" => $totRe,
			"shares" => $totSh			
		);

		$json = json_encode($o);

		$this->load->library('redis');
		$this->redis->command("ZADD minerd_stats ".time()." ".$json);
		
		log_message('error', "Stats stored as: $json");
	}
	
	/*
	//
	// Crypto rates related stuff
	//
	*/
	
	// Get Bitstamp API to look at BTC/USD rates
	public function getBtcUsdRates()
	{
		if ($json = @file_get_contents("https://www.bitstamp.net/api/ticker/"))
		{
			$a = json_decode($json);
			return $a;
		}
		else
		{
			return false;
		}
	}
	
	// Get Cryptsy API to look at LTC/BTC rates
	public function getCryptsyRates($id)
	{
		if ($json = @file_get_contents("http://pubapi.cryptsy.com/api.php?method=singlemarketdata&marketid=$id"))
		{
			$a = json_decode($json);
			return $a;
		}
		else
		{
			return false;
		}
	}
	
	
	/*
	//
	// Miner and System related stuff
	//
	*/
	
	// Check if the minerd if running
	public function isOnline()
	{
		if(!($fp = @fsockopen("127.0.0.1", 4028, $errno, $errstr, 1)))
		{
			return false;
		}
		
		return true;
	}
	
	// Check RPi temp
	public function checkTemp()
	{
		if (file_exists($this->config->item("rpi_temp_file")))
		{
			$temp = exec("cat ".$this->config->item("rpi_temp_file"));
			return number_format($temp/1000, 2);
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
	
	// Call shutdown cmd
	public function shutdown()
	{
		log_message('error', "Shutdown cmd called");
		
		exec("sudo shutdown -h now");

		return true;
	}

	// Call reboot cmd
	public function reboot()
	{
		log_message('error', "Reboot cmd called");
		
		exec("sudo reboot");

		return true;
	}
	
	// Write rc.local startup file
	public function saveStartupScript($delay = 5, $extracommands = false)
	{
		$command = array($this->config->item("screen_command"), $this->config->item("minerd_command"), $this->redis->get('minerd_settings'));
		
		$rcLocal = file_get_contents(FCPATH."rc.local.minera");
		
		$rcLocal .= "\nsleep $delay\nsu - ".$this->config->item('system_user').' -c "'.implode(' ', $command)."\"\n$extracommands\nexit 0";
		
		file_put_contents('/etc/rc.local', $rcLocal);
		
		log_message('error', "Startup script saved: ".var_export($rcLocal, true));

		return true;
	}

	public function saveCurrentFreq()
	{
		return $this->cpuminer_model->saveCurrentFreq();
	}
		
	// Stop miner
	public function minerStop()
	{
		return $this->cpuminer_model->stop();
	}
	
	// Start miner
	public function minerStart()
	{
		return $this->cpuminer_model->start();
	}
	
	// Stop minerd
	public function minerRestart()
	{
		return $this->cpuminer_model->restart();
	}
	
	// Call update cmd
	public function update()
	{
		// Pull the latest code from github
		exec("cd ".FCPATH." && sudo -u " . $this->config->item("system_user") . " sudo /usr/bin/git pull -v", $out);
		log_message('error', "Update request from ".$this->currentVersion()." to ".$this->redis->command("HGET minera_update new_version")." : ".var_export($out, true));
				
		// Run upgrade script
		exec("cd ".FCPATH." && sudo -u " . $this->config->item("system_user") . " sudo ./upgrade_minera.sh", $out);
		log_message('error', "Running upgrade script".var_export($out, true));
				
		$this->redis->del("minera_update");
		$this->redis->del("minera_version");
		$this->checkUpdate();
		log_message('error', "End Update");
		
		return true;
	}
	
	// Check Minera version
	public function checkUpdate()
	{
		// wait 1h before recheck
		if (time() > ($this->redis->command("HGET minera_update timestamp")+3600))
		{
			log_message('error', "Checking Minera updates");
			
			$this->redis->command("HSET minera_update timestamp ".time());

			$latestConfig = json_decode(file_get_contents($this->config->item("remote_config_url")));

			$localVersion = $this->currentVersion();
			$this->redis->command("HSET minera_update new_version ".$latestConfig->version);

			if ($latestConfig->version != $localVersion)
			{
				log_message('error', "Found a new Minera update");

				$this->redis->command("HSET minera_update value 1");
				return true;
			}
		
			$this->redis->command("HSET minera_update value 0");			
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
	public function currentVersion()
	{
		// wait 1h before recheck
		if (time() > ($this->redis->command("HGET minera_version timestamp")+3600))
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
	
	// Check Internet connection
	public function checkConn()
	{
		if(!fsockopen("www.google.com", 80)) {
			return false;
		}
		
		return true;
	}
}
