<?php
/*
 * Pirate_model
 * Pirate model for raspinode
 *
 * @author Hades
 */
class Pirate_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}
	
	// Call minerd to get the stats and retry before give up
	public function callMinerd($cmd = false)
	{		
		if(!($fp = @fsockopen("127.0.0.1", 11888, $errno, $errstr, 3)))
		{
			return array("error" => true, "msg" => $errstr);
		}
	
		stream_set_blocking($fp, false);
		
		if (!$cmd)
			$in = json_encode(array("get" => "stats"))."\n";
		else
			$in = json_encode($cmd)."\n";
			
		log_message("error", "Called Minerd with command: ".$in);

		$out = false;
		
		return json_decode($out);		
	}
	
}
