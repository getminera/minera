<?php if (!defined('BASEPATH')) die();
class App extends Main_Controller {

	/*
	// Index/lock screen controller
	*/
	public function index()
	{	
		$data['htmlTag'] = "lockscreen";
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
		
		$data['btc'] = $this->util_model->getBtcUsdRates();
		$data['ltc'] = $this->util_model->getCryptsyRates(3);
		$data['doge'] = $this->util_model->getCryptsyRates(132);
		$data['isOnline'] = $this->util_model->isOnline();
		$data['htmlTag'] = "dashboard";
		$data['appScript'] = true;
		$data['mineraUpdate'] = $this->util_model->checkUpdate();
		$data['dashboard_refresh_time'] = $this->redis->get("dashboard_refresh_time");
		
		$this->load->view('include/header', $data);
		$this->load->view('include/sidebar', $data);
		$this->load->view('frontpage', $data);
		$this->load->view('include/footer');
	}

	/*
	// Settings controller
	*/
	public function settings()
	{
		if (!$this->session->userdata("loggedin"))
			redirect('app/index');

		if ($this->input->post('save_settings'))
		{
			$settings = trim($this->input->post('minerd_settings'));
			if (!empty($settings))
			{
				$this->redis->set("minerd_settings", $settings);
				$this->redis->set("minerd_autorecover", $this->input->post('minerd_autorecover'));
				
				$this->util_model->saveStartupScript();
				
				$data['message'] = '<b>Success!</b> Settings saved!';
				$data['message_type'] = "success";
			}
			else
			{
				$data['message'] = "<b>Warning!</b> Minerd options can't be empty";
				$data['message_type'] = "warning";
			}
		}
		
		if ($this->input->post('save_dashboard_settings'))
		{
			$dashSettings = trim($this->input->post('dashboard_refresh_time'));
			if (!empty($dashSettings) && !is_integer($dashSettings) && $dashSettings >= 5)
			{
				$this->redis->set("dashboard_refresh_time", $dashSettings);
				
				$data['message'] = '<b>Success!</b> Settings saved!';
				$data['message_type'] = "success";
			}
			else
			{
				$data['message'] = "<b>Warning!</b> Refresh time must be integer and >= 5";
				$data['message_type'] = "warning";
			}
		}

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
		
		$data['btc'] = $this->util_model->getBtcUsdRates();
		$data['ltc'] = $this->util_model->getCryptsyRates(3);
		$data['doge'] = $this->util_model->getCryptsyRates(132);
		$data['isOnline'] = $this->util_model->isOnline();
		$data['minerd_autorecover'] = $this->redis->get('minerd_autorecover');
		$data['dashboard_refresh_time'] = $this->redis->get("dashboard_refresh_time");
		$data['mineraUpdate'] = $this->util_model->checkUpdate();
		$data['htmlTag'] = "settings";
		$data['appScript'] = false;
		
		$this->load->view('include/header', $data);
		$this->load->view('include/sidebar', $data);
		$this->load->view('settings', $data);
		$this->load->view('include/footer');
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
		$data['messageEnd'] = "you can unplug me now.";
		$data['htmlTag'] = "lockscreen";
		$data['seconds'] = 60;
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
		$data['messageEnd'] = "here we go!";
		$data['htmlTag'] = "lockscreen";
		$data['seconds'] = 30;
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
		
		$this->util_model->minerdStart();
		
		redirect('app/dashboard');
	}

	/*
	// Stop miner controller (this should be in a different "system" controller file)
	*/
	public function stop_miner()
	{
		if (!$this->session->userdata("loggedin"))
			redirect('app/index');
		
		$this->util_model->minerdStop();
		
		redirect('app/dashboard');
	}
	
	/*
	// Update controller (this should be in a different "system" controller file)
	*/
	public function update()
	{	
		if ($this->input->get('confirm'))
		{
			$data['message'] = "Please wait while I'm upgrading the system...";
			$data['timer'] = true;
			$this->util_model->update();
		}
		else
		{
			$data['title'] = "System update detected";
			$data['message'] = "Please SSH in minera and run the following commands:<p><code>cd ".FCPATH."<br />sudo git pull</code></p><p>Then come back here and go to the <a href='".site_url("app/dashboard")."'>dashboard</a>. <small>(An auto-update tool is coming asap)</small>";
			//$data['message'] = '<a href="'.site_url("app/update").'?confirm=1"><button class="btn btn-default btn-lg"><i class="fa fa-check"></i> Let me install the updates</button></a>&nbsp;&nbsp;&nbsp;<a href="'.site_url("app/dashboard").'"><button class="btn btn-default btn-lg"><i class="fa fa-times"></i> No, thanks</button></a>';
			$data['timer'] = false;
		}
		$data['messageEnd'] = "System updated!";
		$data['htmlTag'] = "lockscreen";
		$data['seconds'] = 30;
		$data['refreshUrl'] = false;//site_url("app/index");
		$this->load->view('include/header', $data);
		$this->load->view('sysop', $data);
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
		// Check and restart the minerd if it's dead
		if ($this->redis->get('minerd_autorecover'))
		{
			$this->util_model->checkMinerIsUp();	
		}
		
		// Store the live stats to be used on time graphs
		$this->util_model->storeStats();
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
