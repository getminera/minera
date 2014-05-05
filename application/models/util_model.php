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
		parent::__construct();
	}

	/*
	//
	// Stats related stuff
	//
	*/
	
	// Get the live stats from cpuminer
	public function getStats()
	{
		$tmpFile = $this->config->item("tmp_stats_file");
		
		if ($this->isOnline())
		{
			if(!($fp = fsockopen("127.0.0.1", 4028, $errno, $errstr, 0)))
			{
				return json_encode(array("error" => true));
			}
			
			stream_set_blocking($fp, false);
			
			$out = json_encode(array("get" => "stats"))."\n";
			
			fwrite($fp, $out);
			
			usleep(100000);
			
			$out = "";
			
			while(!feof($fp))
			{
			    if(!($str = fgets($fp, 2048))) break;
			    $out .= $str;
			}
			
			fclose($fp);
			
			$a = json_decode($out);
			
			if ($a)
			{
				$a = (array)$a;
				$a["sysload"] = sys_getloadavg();
				
				$o = json_encode($a);
				file_put_contents($tmpFile, $o);
				return $o;
			}
			else
			{
				if (file_exists($tmpFile))
				{
					return file_get_contents($tmpFile);
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
	public function getStoredStats()
	{	
		$now = time();
		$onehourago = $now-3600;
		$this->load->library('redis');
		$o = $this->redis->command("ZRANGEBYSCORE minerd_stats $onehourago $now");
		
		return $o;
	}

	// Store the live stats on Redis
	public function storeStats()
	{		
		$data = json_decode($this->getStats());
		
		$totHash = 0; $totFreq = 0; $totAc = 0; $totHw = 0; $totRe = 0; $totSh = 0; $d = 0; $c = 0;
		
		if ($data->d && count($data->d) > 0)
		{
			foreach($data->d as $device)
			{
				$d++;
				if ($device->c && count($device->c) > 0)
				{
					foreach($device->c as $chip)
					{
						$c++;
						$totHash += $chip->ha;
						$totFreq += $chip->fr;
						$totAc += $chip->ac;
						$totHw += $chip->hw;
						$totRe += $chip->re;
						$totSh += $chip->sh;
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
	}
	
	/*
	//
	// Crypto rates related stuff
	//
	*/
	
	// Get Bitstamp API to look at BTC/USD rates
	public function getBtcUsdRates()
	{
		if ($json = file_get_contents("https://www.bitstamp.net/api/ticker/"))
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
		if ($json = file_get_contents("http://pubapi.cryptsy.com/api.php?method=singlemarketdata&marketid=$id"))
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
		exec('ps -ef|grep "minera-bin/minerd" | grep -v grep|grep -v -i screen', $minerd);

		if (count($minerd) > 0)
			return true;
		
		return false;
	}
	
	// Call shutdown cmd
	public function shutdown()
	{
		exec("sudo shutdown -h now");

		return true;
	}

	// Call reboot cmd
	public function reboot()
	{
		exec("sudo reboot");

		return true;
	}
	
	// Write rc.local startup file
	public function saveStartupScript()
	{
		$command = array($this->config->item("screen_command"), $this->config->item("minerd_command"), $this->redis->get('minerd_settings'));
		
		$rcLocal = file_get_contents(FCPATH."rc.local.minera");
		
		file_put_contents('/etc/rc.local', $rcLocal."\nsu - ".$this->config->item('system_user').' -c "'.implode(' ', $command)."\"\n\nexit 0");

		return true;
	}
	
	// Stop minerd
	public function minerdStop()
	{
		exec("sudo -u " . $this->config->item("system_user") . " " . $this->config->item("screen_command_stop"));
		exec("sudo -u " . $this->config->item("system_user") . " killall minerd", $test);

		if (file_exists($this->config->item("tmp_stats_file")))
			unlink($this->config->item("tmp_stats_file"));
			
		return true;
	}
	
	// Start minerd
	public function minerdStart()
	{
		$command = array($this->config->item("screen_command"), $this->config->item("minerd_command"), $this->redis->get('minerd_settings'));

		exec("sudo -u " . $this->config->item("system_user") . " " . implode(" ", $command));

		return true;
	}
}
