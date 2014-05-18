<?php
/*
 * Cpuminer_model
 * CPUminer model for minera
 *
 * @author michelem
 */
class Cpuminer_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}
	
	// Call minerd to get the stats and retry before give up
	public function callMinerd($i = 0)
	{
		if(!($fp = @fsockopen("127.0.0.1", 4028, $errno, $errstr, 3)))
		{
			return array("error" => true, "msg" => $errstr);
		}
	
		stream_set_blocking($fp, false);
		
		$out = json_encode(array("get" => "stats"))."\n";
		
		fwrite($fp, $out);
		
		usleep(250000);
		
		$out = false;
		
		while(!feof($fp))
		{
		    if(!($str = fread($fp, 4096))) break;
		    $out .= $str;
		}

		fclose($fp);
			
		return json_decode($out);
	}
	
	public function saveCurrentFreq()
	{
		$stats = json_decode($this->util_model->getStats());

		$dev = array();
		foreach ($stats->devices as $d => $device)
		{
			foreach ($device->chips as $c => $chip)
			{
				$fr = $chip->frequency;
				$dev[] = $device->serial.":".$fr.":".$c;
			}
		}
		
		$r = implode(",", $dev);
		
		$this->redis->set("current_frequencies", $r);
		
		return $r;
	}
	
	// Stop minerd
	public function stop()
	{
		exec("sudo -u " . $this->config->item("system_user") . " " . $this->config->item("screen_command_stop"));
		exec("sudo -u " . $this->config->item("system_user") . " /usr/bin/killall -s9 minerd");
		
		$this->redis->del("latest_stats");
		$this->redis->set("minerd_status", false);
		
		log_message('error', "Minerd stopped");
					
		return true;
	}
	
	// Start minerd
	public function start()
	{
		$command = array($this->config->item("screen_command"), $this->config->item("minerd_command"), $this->redis->get('minerd_settings'));

		$finalCommand = "sudo -u " . $this->config->item("system_user") . " " . implode(" ", $command);
		
		exec($finalCommand, $out);
		
		$this->redis->set("minerd_status", true);
		
		log_message('error', "Minerd started with command: $finalCommand - Output was: ".var_export($out, true));

		return true;
	}
	
	// Restart minerd
	public function restart()
	{
		$this->minerdStop();
		sleep(1);
		$this->minerdStart();
		sleep(1);
					
		return true;
	}
	
}
