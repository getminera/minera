<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// App specific configs
// $config['ENV'] = 'development';
$config['ENV'] = 'production';
$config['minera_api_url'] = 'https://getminera.com/api';
$config['live_stats_url'] =  'app/stats';
$config['stored_stats_url'] =  'app/api?command=history_stats&type=hourly';
$config['tmp_stats_file'] = '/tmp/cm_latest_stats';
$config['remote_config_url'] = 'https://raw.githubusercontent.com/getminera/minera/master/minera.json';
$config['rpi_temp_file'] = '/sys/class/thermal/thermal_zone0/temp';
/* End of file autoload.php */
/* Location: ./application/config/app.php */
