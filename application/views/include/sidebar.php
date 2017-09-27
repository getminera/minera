    <body class="skin-<?php echo $dashboardSkin ?>" onload="getStats(false);">
		<div class="app_data"
			data-refresh-time="<?php echo ($dashboard_refresh_time) ? $dashboard_refresh_time : 60; ?>"
			data-records-per-page="<?php echo ($dashboardTableRecords) ? $dashboardTableRecords : 5; ?>"
			data-minerd-log="<?php echo ($minerdLog) ? base_url($this->config->item("minerd_log_url")) : null; ?>"
			data-device-tree="<?php echo $dashboardDevicetree ?>"
			data-dashboard-temp="<?php echo ($this->redis->get("dashboard_temp")) ? $this->redis->get("dashboard_temp") : "c"; ?>"
			data-miner-status="<?php echo ($this->redis->get("minerd_status")) ? 1 : 0; ?>"
			data-miner-running="<?php echo $minerdRunning; ?>"
			data-minera-pool-username="<?php echo $this->util_model->getMineraPoolUser(); ?>"
			data-minera-pool-url-scrypt="<?php echo $this->config->item('minera_pool_url') ?>"
			data-minera-pool-url-sha256="<?php echo $this->config->item('minera_pool_url_sha256') ?>"
			data-ads-free="<?php echo $adsFree ?>"
			data-browser-mining="<?php echo $browserMining ?>"
			data-browser-mining-threads="<?php echo $browserMiningThreads ?>"
			data-minera-id="<?php echo $mineraSystemId ?>"
		></div>

		<!-- Modal -->
		<div id="modal-saving" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="SavingData" aria-hidden="true" data-backdrop="static" data-keyboard="false" >
			<div class="modal-dialog modal-dialog-center modal-md">
				<div class="modal-content">
					<div class="modal-header bg-red">
						<h4 class="modal-title" id="modal-saving-label"></h4>
					</div>
					<div class="modal-body" style="text-align:center;">
						<img src="<?php echo base_url("assets/img/ajax-loader1.gif") ?>" alt="Loading..." />
					</div>
					<div class="modal-footer modal-footer-center">
						<h6>Page will automatically reload as soon as the process terminate.</h6>
					</div>
				</div>
			</div>
		</div>
		
		<div id="modal-promo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="Promo" aria-hidden="true" data-backdrop="static" data-keyboard="false" >
			<div class="modal-dialog modal-dialog-center modal-md">
				<div class="modal-content">
					<div class="modal-header bg-primary">
						<button type="button" class="close modal-promo-hide"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<h4 class="modal-title"><i class="fa fa-gift"></i> Support Minera</h4>
					</div>
					<div class="modal-body">
						<p>Please support the Minera project. This message will appear each hour. You can <a href="<?php echo site_url("app/settings#donation-box") ?>">completely remove the Ads on your Minera system</a>.</p>
						<div class="mt30 mb20 text-center">
							<div class="row">
								<div class="banner col-md-offset-1 col-md-5 promo-iframe" bannerid='promo1'><?php echo $ads['200x200'] ?></div>
								<div class="banner col-md-5 promo-iframe" bannerid='promo2'><?php echo $ads['200x200'] ?></iframe></div>
							</div>
						</div>
					</div>
					<div class="modal-footer modal-footer-center">
						<a href="<?php echo site_url("app/settings#donation-box") ?>" class="btn btn-sm btn-primary">Remove Ads</a>
					</div>
				</div>
			</div>
		</div>
		
		<div id="modal-log" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="Logs" aria-hidden="true" data-backdrop="static" data-keyboard="false" >
			<div class="modal-dialog modal-dialog-center modal-md">
				<div class="modal-content">
					<div class="modal-header bg-red">
						<h4 class="modal-title" id="modal-log-label"></h4>
					</div>
					<div class="modal-body">
						<p>Please take care of the lines below, here you could find the problem why your miner is not running:</p>
						<blockquote class="modal-log-lines"></blockquote>
					</div>
					<div class="modal-footer">
						<h6 class="pull-left">if you are still in trouble please check also <a href="<?php echo base_url($this->config->item("minerd_log_url")); ?>" target="_blank"><i class="fa fa-briefcase"></i> the full log here</a></h6>
						<button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		
		<div id="modal-sharing" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="SharingData" aria-hidden="true" data-backdrop="static" data-keyboard="false" >
			<div class="modal-dialog modal-md">
				<div class="modal-content">
					<div class="modal-header bg-blue">
						<h4 class="modal-title" id="modal-sharing-label"><i class="fa fa-share-square-o"></i> Share your config</h4>
					</div>
					<div class="modal-body">
						<p>Before you can share your config with the Minera community you need to add a description, please add helpful infos like devices used and notes for users.<br />Only miner software and miner settings along with this description will be shared, no pools info.</p>
						<form method="post" id="formsharingconfig">
							<div class="form-group">
								<label>Config description</label>
								<textarea name="config_description" class="form-control" rows="5" placeholder="Example: Used with Gridseed Blade and Zeus Blizzard, adjust clock and chips for your needs" class="config-description"></textarea>
								<input type="hidden" name="config_id" value="" />
							</div>
						</form>
						<h6>Each config will be moderated before being available in the public repository. (Available soon on <a href="http://getminera.com">Getminera.com</a>)</h6>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary share-config-action" data-config-id="">Share config</button>
					</div>
				</div>
			</div>
		</div>
		
		<div id="modal-terminal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
			<div class="modal-dialog modal-dialog-center modal-terminal">
				<div class="modal-content">
					<div class="modal-header bg-blue">
						<button type="button" class="close modal-hide"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<h4 class="modal-title" id="modal-saving-label"><i class="fa fa-terminal"></i> Minera terminal window</h4>
					</div>
					<div class="modal-body bg-black" style="text-align:center;">
						<iframe id="terminal-iframe" src="" style="" width="100%" height="450" frameborder="0"></iframe>
					</div>
					<div class="modal-footer modal-footer-center">
						<h6>This is a full terminal window running on your Minera system, use any user you want to login, but remember Minera runs as user "minera" and you should use this for each operation you wanna do.</h6>
					</div>
				</div>
			</div>
		</div>
		
        <header class="header" data-this-section="<?php echo $sectionPage ?>">

            <a href="<?php echo site_url('app/dashboard') ?>" class="logo">Minera</a>

            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                
				<div class="navbar-right">
					<ul class="nav navbar-nav">
						<!-- Cron status -->
						<li class="messages-menu cron-status" style="display:none;">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-spin fa-cog"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">Cron is running</li>
                                <li>
                                	<ul class="menu">
	                                	<li class="p10">
		                                	Cron process runs every minute and should take not more than 30 seconds to terminate.<br />
		                                	If this icon is permanent, click the button below to unlock the cron.
		                                	<div class="text-center mt10">
			                                	<button class="btn btn-default cron-unlock">Unlock cron</button>
		                                	</div>
	                                	</li>
                                	</ul>
                                </li>
                            </ul>
                        </li>
						<!-- Clock -->
						<li class="messages-menu">
                            <a href="#" class="clock">
                                <i class="fa fa-clock-o"></i> <span class="toptime"></span>
                            </a>
                        </li>

                        <!-- Browser mining -->
                        <?php if ($browserMining) : ?>
						<li class="messages-menu messages-bm">
                            <a href="#" class="bmWarm">
                                <i class="fa fa-globe"></i> <span>Warming up...</span>
                            </a>
                            <a href="#" class="dropdown-toggle bmHash" data-toggle="dropdown" style="display:none;">
                                <i class="fa fa-globe"></i> <span class="bmHashText"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-mining">
                                <li class="header">Browser mining</li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu avg-stats" style="overflow: hidden; width: 100%; height: 200px;">
	                                   	<li>
											<a href="#">
												<div class="pull-left" style="padding-left:15px;">
													<i class="fa fa-trophy"></i>
												</div>
												<h4><span class="bmAccepted"></span><small><i class="fa fa-globe"></i> Accepted hashes</small></h4>
												<p>Browser mining hash</p>
											</a>
										</li>
										<li>
											<div class="text-center">
												<h1><a href="#" data-toggle="tooltip" title="Increase CPU threads" class="increase-mining-threads"><i class="fa fa-plus"></i></a> <span class="bm-threads ml10 mr10 fs72"></span> <a href="#" data-toggle="tooltip" title="Decrease CPU threads" class="decrease-mining-threads"><i class="fa fa-minus"></i></a></h1>
											</div>
										</li>
                                    </ul>
                                </li>
                                <li class="footer"><a href="<?php echo site_url("app/settings") ?>">Go to settings</a></li>
                            </ul>
						</li>
						<?php endif; ?>

                        <!-- Averages -->
						<li class="messages-menu messages-avg">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-dashboard"></i> <span>Calculating...</span>
                            </a>
							<!-- BEGIN: Underscore Template Definition. -->
							<script type="text/template" class="avg-stats-template">
	                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
	                                <i class="fa fa-dashboard"></i> <span><%- rc.avgonemin %> <i class="fa <%= rc.arrow %>" style="color:<%= rc.color %>;"></i></span>
	                            </a>
	                            <ul class="dropdown-menu">
	                                <li class="header">Average stats</li>
	                                <li>
	                                    <!-- inner menu: contains the actual data -->
	                                    <ul class="menu avg-stats" style="overflow: hidden; width: 100%; height: 200px;">
		                                    <%	_.each( rc.avgs, function( avg ) { %>
												<li>
													<a href="#">
														<div class="pull-left" style="padding-left:15px;">
															<i class="fa <%= avg.arrow %>" style="color:<%= avg.color %>;"></i>
														</div>
														<h4><%- avg.hrCurrentText %><small><i class="fa fa-dashboard"></i> Pool Hashrate</small></h4>
														<p><%- avg.key %></p>
													</a>
												</li>
											<% }); %>
	                                    </ul>
	                                </li>
	                                <li class="footer"><a href="<?php echo site_url("app/charts") ?>">Go to Charts</a></li>
	                            </ul>
	                        </script>
							<!-- END: Underscore Template Definition. -->
						</li>

						<!-- BTC/USD rates -->
						<li class="messages-menu messages-btc-rates">
							<a href="#" class="dropdown-toggle dropdown-btc-rates" data-toggle="dropdown">
	                            <i class="fa fa-btc"></i> <span class="avg-1min">Getting data...</span>
							</a>
							<!-- BEGIN: Underscore Template Definition. -->
							<script type="text/template" class="btc-rates-template">
	                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
	                                <i class="fa fa-btc"></i> price: <%- rc.btc_rates.last %> <i class="fa fa-dollar"></i> <span class="small">(<%- rc.btc_rates.last_eur %> <i class="fa fa-eur"></i>)</span>
	                            </a>
	                            <ul class="dropdown-menu">
	                                <li class="header">Data from Bitstamp</li>
	                                <li>
	                                    <!-- inner menu: contains the actual data -->
	                                    <ul class="menu" style="overflow: hidden; width: 100%;">
	                                        <li>
	                                            <a href="#">
	                                            	<div class="pull-left" style="padding-left:15px;">
	                                                    <i class="fa fa-archive"></i>
	                                                </div>
	                                                <h4>
	                                                    <%- rc.btc_rates.volume %>
	                                                    <small><i class="fa fa-clock-o"></i> <%- moment(rc.btc_rates.timestamp, 'X').format('hh:mm:ss a') %></small>
	                                                </h4>
	                                                <p>Volume</p>
	                                            </a>
	                                        </li>
	                                        <li>
	                                            <a href="#">
	                                            	<div class="pull-left" style="padding-left:15px;">
	                                                    <i class="fa fa-arrow-circle-up"></i>
	                                                </div>
	                                                <h4>
	                                                    <%- rc.btc_rates.high %> <i class="fa fa-dollar"></i> <span class="small">(<%- rc.btc_rates.high_eur %> <i class="fa fa-eur"></i>)</span>
	                                                    <small><i class="fa fa-clock-o"></i> <%- moment(rc.btc_rates.timestamp, 'X').format('hh:mm:ss a') %></small>
	                                                </h4>
	                                                <p>High</p>
	                                            </a>
	                                        </li>
	                                        <li>
	                                            <a href="#">
	                                            	<div class="pull-left" style="padding-left:15px;">
	                                                    <i class="fa fa-arrow-circle-down"></i>
	                                                </div>
	                                                <h4>
	                                                    <%- rc.btc_rates.low %> <i class="fa fa-dollar"></i> <span class="small">(<%- rc.btc_rates.low_eur %> <i class="fa fa-eur"></i>)</span>
	                                                    <small><i class="fa fa-clock-o"></i> <%- moment(rc.btc_rates.timestamp, 'X').format('hh:mm:ss a') %></small>
	                                                </h4>
	                                                <p>Low</p>
	                                            </a>
	                                        </li>
	                                        <li>
	                                            <a href="#">
	                                            	<div class="pull-left" style="padding-left:15px;">
	                                                    <i class="fa fa-exchange"></i>
	                                                </div>
	                                                <h4>
	                                                    1 <i class="fa fa-eur"></i> / <%- rc.btc_rates.eur_usd %> <i class="fa fa-dollar"></i>
	                                                    <small><i class="fa fa-clock-o"></i> <%- moment(rc.btc_rates.timestamp, 'X').format('hh:mm:ss a') %></small>
	                                                </h4>
	                                                <p>Eur/Usd Rate</p>
	                                            </a>
	                                        </li>
	                                    </ul>                                </li>
	                                <li class="footer"><a href="https://www.bitstamp.net">Go to Bitstamp</a></li>
	                            </ul>
	                        </script>
							<!-- END: Underscore Template Definition. -->
						</li>
                        
					    <!-- Donate/Help dropdown -->
					    <li class="dropdown user user-menu">
					        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
					            <i class="glyphicon glyphicon-gift"></i>
					            <span>Help <i class="caret"></i></span>
				                	<?php if ($mineraUpdate) : ?><span class="label label-danger"><i class="fa fa-info"></i></span><?php endif; ?>
					        </a>
					        <ul class="dropdown-menu">
					            <!-- User image -->
					            <li class="user-header bg-dark-grey">
					            	<p><i class="glyphicon glyphicon-heart"></i></p>
					                <p>
					                    <small>Made with love</small>
					                    Minera is a free and open source software
					                    <small>Please help Minera: spread it, share, donate</small>
					                </p>
					                <div style="margin:22px;">
										<a href="https://www.facebook.com/sharer/sharer.php?u=http://getminera.com" target="_blank"><button class="btn bg-light-blue btn-circle"><i class="fa fa-facebook"></i></button></a>
										<a href="https://twitter.com/home?status=Try%20Minera%20for%20your%20%23bitcoin%20mining%20monitor%20http://getminera.com &#64;michelem" target="_blank"><button class="btn bg-aqua btn-circle"><i class="fa fa-twitter"></i></button></a>
										<a href="https://plus.google.com/share?url=http://getminera.com" target="_blank"><button class="btn bg-red btn-circle"><i class="fa fa-google-plus"></i></button></a>
									</div>
					            </li>
					            <!-- Menu Body -->
					            <li class="user-body">
					                <div class="col-xs-4 text-center">
										<a href="https://github.com/getminera/minera" target="_blank">Github</a>
									</div>
					                <div class="col-xs-4 text-center">
										<a href="https://bitcointalk.org/index.php?topic=596620.0" target="_blank">Forum</a>
									</div>
					                <div class="col-xs-4 text-center">
					                    <a href="http://twitter.com/michelem" target="_blank">Follow</a>					
									</div>
					            </li>
					            <!-- Menu Footer-->
					            <li class="user-footer">
					                <div class="pull-left">
					                    <a href="http://getminera.com" class="btn btn-default btn-flat">Get Minera</a>
					                </div>
					                <div class="pull-right">
					                	<?php if ($mineraUpdate) : ?>
						                    <a href="<?php echo site_url("app/update") ?>" class="btn btn-danger btn-flat" style="color:#fff;">Ver. <?php echo $this->util_model->currentVersion() ?></a>
										<?php else: ?>
						                    <a href="<?php echo base_url("minera.json") ?>" class="btn btn-default btn-flat">Ver. <?php echo $this->util_model->currentVersion() ?></a>
										<?php endif; ?>
					                </div>
					            </li>
			                	<?php if ($mineraUpdate) : ?>
				                <li class="user-footer">
				                	<div class="col-xs-12 text-center">
				                		<small><a href="<?php echo site_url("app/update") ?>">There is a new version available</a></small>
				                		<p><a href="<?php echo site_url("app/update") ?>"><button class="btn btn-danger btn-xs" data-toggle="tooltip" title="" data-original-title="Update Minera">Update Now! <i class="fa fa-download"></i></button></a></p>
				                	</div>
				                </li>
								<?php endif; ?>
					            <li>
					                <div class="col-xs-4 text-center" style="background: green; height: 3px;"></div>
					                <div class="col-xs-4 text-center" style="background: white; height: 3px;"></div>
					                <div class="col-xs-4 text-center" style="background: red; height: 3px;"></div>
					            </li>
					        </ul>
					    </li>
					</ul>
				</div>
			</nav>
        </header>
        
        <!-- Main content -->
        <div class="wrapper row-offcanvas row-offcanvas-left">

			<!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar main panel -->
                    <div class="user-panel">
                        <div class="pull-left info">
                            <p>Minera ID <strong><?php echo $mineraSystemId ?></strong></p>
							<?php if ($isOnline) : ?>
	                            <a href="<?php echo site_url("app/dashboard") ?>"><i class="fa fa-circle text-success"></i> Online <?php if ($minerdRunning) : ?><small class="pull-right badge bg-green"><?php echo $minerdRunning ?></small><?php endif; ?></a>
	                        <?php else: ?>
	                            <a href="<?php echo site_url("app/settings") ?>" data-toggle="tooltip" title="" data-original-title="Go to the settings page"><i class="fa fa-circle text-muted"></i> Offline <?php if ($minerdSoftware) : ?><small class="pull-right badge bg-muted"><?php echo $minerdSoftware ?></small><?php endif; ?></a>
							<?php endif; ?>
                        </div>
                    </div>
					
					<?php if (!$adsFree) : ?>
					<div class="mb10 text-center">
						<?php echo ($dashboardSkin ==='black') ? $ads['200x200_black'] : $ads['200x200']; ?>
					</div>
					<?php endif; ?>
					
                    <!-- sidebar menu -->
                    <ul class="sidebar-menu">
	                    <?php if ($sectionPage === "dashboard" && (($isOnline && $appScript) || count($netMiners) > 0)) : ?>
                        	<li data-toggle="tooltip" title="" data-original-title="Refresh Dashboard">
                            	<a href="#" class="refresh-btn">
                                	<i class="fa fa-refresh refresh-icon mr5" style="width: inherit;"></i> <span>Refresh</span><span class="badge bg-muted pull-right auto-refresh-time">auto in</span>
								</a>
							</li>
						<?php endif; ?>
                        <li class="treeview" data-toggle="tooltip" title="" data-original-title="Go to the dashboard page">
                        	<a href="<?php echo site_url("app/dashboard") ?>">
                        		<i class="fa fa-dashboard"></i> 
                        		<span>Dashboard</span>
                                <i class="treeview-menu-dashboard-icon fa pull-right fa-angle-left"></i>
                        	</a>
                        	<ul class="treeview-menu treeview-menu-dashboard" style="display: none;">
                                <li>
                                	<a href="<?php echo site_url("app/dashboard#box-widgets") ?>" class="menu-top-widgets-box ml10">
                                		<i class="fa fa-th"></i> Widgets
                                	</a>
                                </li>
                                <li>
                                	<a href="<?php echo site_url("app/dashboard#box-local-miner") ?>" class="menu-local-miner-box ml10">
                                		<i class="fa fa-desktop"></i> Local miner
                                	</a>
                                </li>
                                <li>
                                	<a href="<?php echo site_url("app/dashboard#box-local-pools") ?>" class="menu-local-pools-box ml10">
                                		<i class="fa fa-cloud"></i> Local Pools
                                	</a>
                                </li>
                                <?php if (count($netMiners) > 0) : ?>
                                <li>
                                	<a href="<?php echo site_url("app/dashboard#network-details") ?>" class="menu-network-miners-box ml10">
                                		<i class="fa fa-server"></i> Network miners
                                	</a>
                                </li>
                                <li>
                                	<a href="<?php echo site_url("app/dashboard#network-pools-details") ?>" class="menu-network-pools-box ml10">
                                		<i class="fa fa-cloud"></i> Network Pools
                                	</a>
                                </li>
                                <?php endif; ?>
                                <li>
                                	<a href="<?php echo site_url("app/dashboard#box-charts") ?>" class="menu-charts-box ml10">
                                		<i class="fa fa-bar-chart"></i> Charts
                                	</a>
                                </li>
                                <li>
                                	<a href="<?php echo site_url("app/dashboard#box-log") ?>" class="menu-log-box ml10">
                                		<i class="fa fa-file"></i> Log
                                	</a>
                                </li>
                                <li>
                                	<a href="<?php echo site_url("app/dashboard#box-donation") ?>" class="menu-donation-box ml10">
                                		<i class="fa fa-gift"></i> Donation
                                	</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-charts" data-toggle="tooltip" title="" data-original-title="Go to the charts page">
                            <a href="<?php echo site_url("app/charts") ?>">
                                <i class="fa fa-bar-chart-o"></i> <span>Charts</span>
                            </a>
                        </li>
                        <li class="treeview">
                        	<a href="#">
                        		<i class="fa fa-gear"></i> 
                        		<span>Settings</span>
                                <i class="treeview-menu-settings-icon fa pull-right fa-angle-left"></i>
                        	</a>
                        	<ul class="treeview-menu treeview-menu-settings" style="display: none;">
	                        	<li>
                                	<a href="<?php echo site_url("app/settings") ?>" class="menu-donation-box ml10">
                                		<i class="fa fa-gear"></i> General
                                	</a>
                                </li>
                                <?php if (!$adsFree) : ?>
                                <li>
                                	<a href="<?php echo site_url("app/settings") ?>" class="menu-donation-box ml10">
                                		<i class="fa fa-ban"></i> Remove Ads
                                	</a>
                                </li>
                                <?php endif; ?>
                                <li>
                                	<a href="<?php echo site_url("app/settings") ?>" class="menu-donation-box ml10">
                                		<i class="fa fa-gift"></i> Donation
                                	</a>
                                </li>
                                <li>
                                	<a href="<?php echo site_url("app/settings#dashboard-box") ?>" class="menu-dashboard-box ml10">
                                		<i class="fa fa-dashboard"></i> Dashboard
                                	</a>
                                </li>
                                <li>
                                	<a href="<?php echo site_url("app/settings#pools-box") ?>" class="menu-pools-box ml10">
                                		<i class="fa fa-cloud"></i> Pools
                                	</a>
                                </li>
                                <li>
                                	<a href="<?php echo site_url("app/settings#customer-miners-box") ?>" class="menu-customer-miners-box ml10">
                                		<i class="fa fa-desktop"></i> Custom Miners
                                	</a>
                                </li>
                                <li>
                                	<a href="<?php echo site_url("app/settings#local-miner-box") ?>" class="menu-local-miner-box ml10">
                                		<i class="fa fa-gear"></i> Local Miner
                                	</a>
                                </li>
                                <li>
                                	<a href="<?php echo site_url("app/settings#network-miners-box") ?>" class="menu-network-miners-box ml10">
                                		<i class="fa fa-server"></i> Network Miners
                                	</a>
                                </li>
                                <li>
                                	<a href="<?php echo site_url("app/settings#system-box") ?>" class="menu-system-box ml10">
                                		<i class="fa fa-rocket"></i> System
                                	</a>
                                </li>
                                <li>
                                	<a href="<?php echo site_url("app/settings#importexport-box") ?>" class="menu-importexport-box ml10">
                                		<i class="fa fa-code-fork"></i> Import/Export/Share
                                	</a>
                                </li>
                                <li>
                                	<a href="<?php echo site_url("app/settings#user-box") ?>" class="menu-user-box ml10">
                                		<i class="fa fa-user"></i> User
                                	</a>
                                </li>
                                <li>
                                	<a href="<?php echo site_url("app/settings#resets-box") ?>" class="menu-resets-box ml10">
                                		<i class="fa fa-warning"></i> Resets
                                	</a>
                                </li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-desktop"></i>
                                <span>Miner</span>
                                <i class="fa pull-right fa-angle-left"></i>
                            </a>
                            <ul class="treeview-menu" style="display: none;">

                                <li data-toggle="tooltip" title="" data-original-title="<?php echo ($isOnline) ? "It seems your miner is mining. To restart it click below" : "Start your miner"; ?>">
                                	<a href="#" <?php echo ($isOnline) ? '' : 'class="miner-action" data-miner-action="start"'; ?> style="margin-left: 10px;">
                                		<i class="fa fa-arrow-circle-o-up"></i> Start miner
                                	</a>
                                </li>
                                <li data-toggle="tooltip" title="" data-original-title="<?php echo ($isOnline) ? "Stop your miner" : "Your miner is stopped"; ?>">
                                	<a href="#" class="miner-action" data-miner-action="stop" style="margin-left: 10px;">
                                		<i class="fa fa-arrow-circle-o-down"></i> Stop miner
                                	</a>
                                </li>
                                <li data-toggle="tooltip" title="" data-original-title="Restart your miner">
                                	<a href="#" class="miner-action" data-miner-action="restart" style="margin-left: 10px;">
                                		<i class="fa fa-repeat"></i> Restart miner
                                	</a>
                                </li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-rocket"></i>
                                <span>System</span>
                                <i class="fa pull-right fa-angle-left"></i>
                            </a>
                            <ul class="treeview-menu" style="display: none;">
                                <li data-toggle="tooltip" title="" data-original-title="Open Minera's terminal">
                                	<a href="#" class="system-open-terminal" style="margin-left: 10px;">
                                		<i class="fa fa-terminal"></i> Open terminal
                                	</a>
                                </li>
                                <li data-toggle="tooltip" title="" data-original-title="Reboot Minera">
                                	<a href="<?php echo site_url("app/reboot") ?>" style="margin-left: 10px;">
                                		<i class="fa fa-flash"></i> Reboot
                                	</a>
                                </li>
                                <li data-toggle="tooltip" title="" data-original-title="Shutdown Minera">
                                	<a href="<?php echo site_url("app/shutdown") ?>" style="margin-left: 10px;">
                                		<i class="fa fa-power-off"></i> Shutdown
                                	</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </section>
                
                <!-- /.sidebar -->
            </aside>
            
