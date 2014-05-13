<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// App specific configs

$config['live_stats_url'] =  'app/stats';
$config['stored_stats_url'] =  'app/stored_stats';
$config['screen_command'] = '/usr/bin/screen -dmS cpuminer';
$config['screen_command_stop'] = '/usr/bin/screen -S cpuminer -X quit';
$config['minerd_command'] = FCPATH.'minera-bin/minerd';
$config['minerd_start_script_file'] = FCPATH.'minera-bin/start_minerd';
$config['minerd_log_file'] = '/var/log/minera/cpuminer.log';
$config['minerd_log_url'] = 'application/logs/cpuminer.log';
$config['tmp_stats_file'] = '/tmp/cm_latest_stats';
$config['system_user'] = 'minera';
$config['remote_config_url'] = 'https://raw.githubusercontent.com/michelem09/minera/master/minera.json';
$config['rpi_temp_file'] = '/sys/class/thermal/thermal_zone0/temp';
$config['btc_address'] = '1AmREReHNLec9EaW7gLRdW31LNSDA9SGR1';
$config['ltc_address'] = 'LLPmAT9gDwmiSdqwWEZu6mpUDmrNAnYBdC';
$config['doge_address'] = 'DLAHwNxfUTUcePewbkvwvAouny19mcosA7';
/* End of file autoload.php */
/* Location: ./application/config/app.php */
