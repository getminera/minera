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
	public function callMinerd($cmd = false)
	{		
		if(!($fp = @fsockopen("127.0.0.1", 4028, $errno, $errstr, 3)))
		{
			return array("error" => true, "msg" => $errstr);
		}
	
		stream_set_blocking($fp, false);
		
		if (!$cmd)
			$in = json_encode(array("get" => "stats"))."\n";
		else
			$in = json_encode($cmd)."\n";
			
		log_message("error", "Called Minerd with command: ".$in);
		
		fwrite($fp, $in);
		
		usleep(150000);

		$out = false;
		
		/*
		// Many thanks to Sandor111
		*/
		$die = 0;

		while(!feof($fp) && !($str = fgets($fp, 2048)))
			usleep(10000);

		$out .= $str;

		while(!feof($fp) && $die < 10)
		{
			if(!($str = fgets($fp, 1024)))
			{
				$die++;
				usleep(10000);
				continue;
			}

			$die = 0;
			$out .= $str;

		}

		fclose($fp);

		return json_decode($out);		
	}
	
	public function selectPool($poolId, $network = false)
	{
		log_message("error", "Trying to switch pool ".(int)$poolId." to the main one.");
		return $this->callMinerd(array("set" => "pool", "pool" => (int)$poolId));
	}
	
	public function saveCurrentFreq()
	{
		$stats = $this->util_model->getMinerStats();

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
	
}
