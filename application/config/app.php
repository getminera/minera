<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// App specific configs
//$config['ENV'] = 'development';
$config['ENV'] = 'production';
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
$config['system_user'] = 'minera';
$config['remote_config_url'] = 'https://raw.githubusercontent.com/michelem09/minera/master/minera.json';
$config['rpi_temp_file'] = '/sys/class/thermal/thermal_zone0/temp';
$config['btc_address'] = '1AmREReHNLec9EaW7gLRdW31LNSDA9SGR1';
$config['ltc_address'] = 'LLPmAT9gDwmiSdqwWEZu6mpUDmrNAnYBdC';
$config['doge_address'] = 'DLAHwNxfUTUcePewbkvwvAouny19mcosA7';
$config['mobileminer_apikey'] = 'Y8gl9PF6QR22Vv';
$config['mobileminer_url_stats'] = 'https://api.mobileminerapp.com/MiningStatisticsInput';
$config['mobileminer_url_notifications'] = 'https://api.mobileminerapp.com/NotificationsInput';
$config['mobileminer_url_poolsinput'] = 'https://api.mobileminerapp.com/PoolsInput';
$config['mobileminer_url_remotecommands'] = 'https://api.mobileminerapp.com/RemoteCommands';
$config['mobileminera_apikey'] = 'Y8gl9PF6QR22Vv';
$config['mobileminera_url_stats'] = 'http://192.168.1.11:3000/api/miners';
$config['minera_pool_url'] = 'stratum+tcp://ltc.ghash.io:3333';
$config['minera_pool_url_sha256'] = 'stratum+tcp://us1.ghash.io:3333';
$config['minera_pool_username'] = 'michelem.minera';
$config['minera_pool_password'] = 'x';
$config['minera_anonymous_url'] = 'http://getminera.com/api/sendMinerStats';
$config['minera_share_configs_url'] = 'http://getminera.com/api/sendMinerConfig';
/* End of file autoload.php */
/* Location: ./application/config/app.php */
