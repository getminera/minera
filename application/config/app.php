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
$config['system_user'] = 'minera';
$config['remote_config_url'] = 'https://raw.githubusercontent.com/getminera/minera/master/minera.json';
$config['rpi_temp_file'] = '/sys/class/thermal/thermal_zone0/temp';
$config['btc_address'] = '19kDRygdVZUq1ARrht6544CGaPzMnF1Q1b';
$config['ltc_address'] = 'LLPmAT9gDwmiSdqwWEZu6mpUDmrNAnYBdC';
$config['doge_address'] = 'DLAHwNxfUTUcePewbkvwvAouny19mcosA7';
$config['mobileminer_apikey'] = 'Y8gl9PF6QR22Vv';
$config['mobileminer_url_stats'] = 'https://api.mobileminerapp.com/MiningStatisticsInput';
$config['mobileminer_url_notifications'] = 'https://api.mobileminerapp.com/NotificationsInput';
$config['mobileminer_url_poolsinput'] = 'https://api.mobileminerapp.com/PoolsInput';
$config['mobileminer_url_remotecommands'] = 'https://api.mobileminerapp.com/RemoteCommands';
$config['mobileminera_apikey'] = 'Y8gl9PF6QR22Vv';
$config['mobileminera_url_stats'] = 'https://getminera.com/api/miners';
$config['minera_pool_url'] = 'stratum+tcp://us.multipool.us:7777';
$config['minera_pool_url_sha256'] = 'stratum+tcp://us.multipool.us:8888';
$config['minera_pool_username'] = 'michelem.minera';
$config['minera_pool_password'] = 'x';
$config['ads'] = Array(
	'200x200' => '<iframe data-aa="499535" src="//ad.a-ads.com/499535?size=200x200&title_color=ff4d50&title_hover_color=ff0000&text_color=333333&link_color=3C8DBC&link_hover_color=ff0000" scrolling="no" style="width:200px; height:200px; border:0px; padding:0;overflow:hidden" allowtransparency="true"></iframe>',
	'200x200_black' => '<iframe data-aa="499556" src="//ad.a-ads.com/499556?size=200x200&title_color=ff4d50&title_hover_color=&link_color=3C8DBC&background_color=fff&text_color=cccccc&link_hover_color=ff0000" scrolling="no" style="width:200px; height:200px; border:0px; padding:0;overflow:hidden" allowtransparency="true"></iframe>',
	'234x60' => '<iframe data-aa="499550" src="//ad.a-ads.com/499550?size=234x60&title_color=ff4d50&title_hover_color=ff0000&text_color=333333&link_color=3C8DBC&link_hover_color=ff0000" scrolling="no" style="width:234px; height:60px; border:0px; padding:0;overflow:hidden" allowtransparency="true"></iframe>',
	'468x60' => '<iframe data-aa="499552" src="//ad.a-ads.com/499552?size=468x60&title_color=ff4d50&title_hover_color=ff0000&text_color=333333&link_color=3C8DBC&link_hover_color=ff0000" scrolling="no" style="width:468px; height:60px; border:0px; padding:0;overflow:hidden" allowtransparency="true"></iframe>'
);
/* End of file autoload.php */
/* Location: ./application/config/app.php */
