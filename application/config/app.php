<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// App specific configs
// $config['ENV'] = 'development';
$config['ENV'] = 'production';
$config['minera_api_url'] = 'https://getminera.com/api';
$config['live_stats_url'] =  'app/stats';
$config['stored_stats_url'] =  'app/api?command=history_stats&type=hourly';
$config['screen_command'] = '/usr/bin/screen -dmS cpuminer';
$config['screen_command_stop'] = '/usr/bin/screen -S cpuminer -X quit';
$config['minerd_command'] = FCPATH.'minera-bin/minerd';
$config['minerd_start_script_file'] = FCPATH.'minera-bin/start_minerd';
$config['minerd_conf_file'] = FCPATH.'conf/miner_conf.json';
$config['minerd_log_file'] = '/var/log/minera/cpuminer.log';
$config['minerd_log_url'] = 'application/logs/cpuminer.log';
$config['tmp_stats_file'] = '/tmp/cm_latest_stats';
$config['system_user'] = 'pirate';
$config['remote_config_url'] = 'https://raw.githubusercontent.com/getminera/minera/master/minera.json';
$config['rpi_temp_file'] = '/sys/class/thermal/thermal_zone0/temp';
$config['btc_address'] = '19kDRygdVZUq1ARrht6544CGaPzMnF1Q1b';
$config['ltc_address'] = 'LLPmAT9gDwmiSdqwWEZu6mpUDmrNAnYBdC';
$config['doge_address'] = 'DLAHwNxfUTUcePewbkvwvAouny19mcosA7';
/* End of file autoload.php */
/* Location: ./application/config/app.php */
