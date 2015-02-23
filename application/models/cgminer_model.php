<?php
/*
 * Cgminer_model
 * CGminer model for minera
 *
 * @author michelem
 */
class Cgminer_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	function getsock($addr, $port)
	{
		$socket = null;
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if ($socket === false || $socket === null)
		{
			$error = socket_strerror(socket_last_error());
			$msg = "socket create(TCP) failed";
			log_message("error", "$msg '$error'");
			return null;
		}

		$res = socket_connect($socket, $addr, $port);
		if ($res === false)
		{
			$error = socket_strerror(socket_last_error());
			$msg = "socket connect($addr,$port) failed";
			log_message("error", "$msg '$error'");
			socket_close($socket);
			return null;
		}

		return $socket;
	}
	
	function readsockline($socket)
	{
		$line = '';
		while (true)
		{
			$byte = socket_read($socket, 1);
			if ($byte === false || $byte === '')
				break;
			if ($byte === "\0")
				break;
			$line .= $byte;
		}
		return $line;
	}
	
	
	function callMinerd($cmd = false, $network = false)
	{
		if (!$cmd)
			$cmd = '{"command":"summary+devs+pools"}';
 
		log_message("error", "Called Minerd with command: ".$cmd);
		
		$ip = "127.0.0.1"; $port = 4028;
		if ($network) list($ip, $port) = explode(":", $network);
		
		$socket = $this->getsock($ip, $port);
		if ($socket != null)
		{
			socket_write($socket, $cmd, strlen($cmd));
			$line = $this->readsockline($socket);
			socket_close($socket);

			if (strlen($line) == 0)
			{
				$msg = "WARN: '$cmd' returned nothing\n";
				return array("error" => true, "msg" => $msg);
			}
		
			//print "$cmd returned '$line'\n";
		
			if (substr($line,0,1) == '{')
				return json_decode($line);
			
			$data = array();
			
			$objs = explode('|', $line);
			foreach ($objs as $obj)
			{
				if (strlen($obj) > 0)
				{
					$items = explode(',', $obj);
					$item = $items[0];
					$id = explode('=', $items[0], 2);
					if (count($id) == 1 or !ctype_digit($id[1]))
						$name = $id[0];
					else
						$name = $id[0].$id[1];
			
					if (strlen($name) == 0)
						$name = 'null';
			
					if (isset($data[$name]))
					{
						$num = 1;
						while (isset($data[$name.$num]))
							$num++;
						$name .= $num;
					}
			
					$counter = 0;
					foreach ($items as $item)
					{
						$id = explode('=', $item, 2);
						if (count($id) == 2)
							$data[$name][$id[0]] = $id[1];
						else
							$data[$name][$counter] = $id[0];
			
						$counter++;
					}
				}
			}
			
			if (isset($data->STATUS->STATUS) && $data->STATUS->STATUS == 'E') {
				return array("error" => true, "msg" => $data->STATUS->Msg);				
			}
			
			return $data;
		}
		
		return array("error" => true, "msg" => "Miner error");
	}
	
	public function selectPool($poolId, $network)
	{
		log_message("error", "Trying to switch pool ".(int)$poolId." to the main one.");
		return $this->callMinerd('{"command":"switchpool", "parameter":'.(int)$poolId.'}', $network);
	}
	
	public function addPool($url, $user, $pass, $network)
	{
		log_message("error", "Trying to add pool parameter:".$url.",".$user.",".$pass);
		return $this->callMinerd('{"command":"addpool", "parameter":'.$url.','.$user.','.$pass.'}', $network);
	}
	
	public function removePool($poolId, $network)
	{
		log_message("error", "Trying to remove pool ".(int)$poolId);
		return $this->callMinerd('{"command":"removepool", "parameter":'.(int)$poolId.'}', $network);
	}
	
}
