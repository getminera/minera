    <body class="skin-<?php echo $dashboardSkin ?>"<?php if ($appScript) : ?> onload="getStats(false);"<?php endif; ?>>

		<!-- Modal -->
		<div id="modal-saving" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="SavingData" aria-hidden="true" data-backdrop="static" data-keyboard="false" >
			<div class="modal-dialog modal-dialog-center modal-sm">
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
		
		<div id="modal-sharing" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="SharingData" aria-hidden="true" data-backdrop="static" data-keyboard="false" >
			<div class="modal-dialog modal-sm">
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
						<iframe src="" style="" width="100%" height="450" frameborder="0"></iframe>
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
                
				<?php if (!$this->redis->get("minera_donation_time")) : ?>
	                <div class="tip-button changetip_tipme_button" data-bid="gQGgJrG52qXazz5HqPX5sD" data-uid="b3JpMBt2XgQw5EVrz5QV86"></div><script>(function(document,script,id){var js,r=document.getElementsByTagName(script)[0],protocol=/^http:/.test(document.location)?'http':'https';if(!document.getElementById(id)){js=document.createElement(script);js.id=id;js.src=protocol+'://widgets.changetip.com/public/js/widgets.js';r.parentNode.insertBefore(js,r)}}(document,'script','changetip_w_0'));</script>
				<?php endif; ?>
                
				<div class="navbar-right">
					<ul class="nav navbar-nav">
						<!-- Clock -->
						<li class="messages-menu">
                            <a href="#" class="clock">
                                <i class="fa fa-clock-o"></i> <span class="toptime"></span>
                            </a>
                        </li>
                        <?php if ($appScript) : ?>
                        <!-- Averages -->
						<li class="messages-menu messages-avg">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-dashboard"></i> <span class="avg-1min">Calculating...</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">Average stats</li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu avg-stats" style="overflow: hidden; width: 100%; height: 200px;"></ul>
                                </li>
                                <li class="footer"><a href="<?php echo site_url("app/charts") ?>">Go to Charts</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
						<?php if (isset($btc->volume)) : ?>
						<!-- BTC/USD rates -->
						<li class="messages-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-btc"></i> price: <?php echo $btc->last ?> <i class="fa fa-dollar"></i> <span class="small">(<?php echo $btc->last_eur ?> <i class="fa fa-eur"></i>)</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">Data from Bitstamp</li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu" style="overflow: hidden; width: 100%; height: 300px;">
                                        <li>
                                            <a href="#">
                                            	<div class="pull-left" style="padding-left:15px;">
                                                    <i class="fa fa-archive"></i>
                                                </div>
                                                <h4>
                                                    <?php echo $btc->volume ?>
                                                    <small><i class="fa fa-clock-o"></i> <?php echo date("H:i", $btc->timestamp) ?></small>
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
                                                    <?php echo $btc->high ?> <i class="fa fa-dollar"></i> <span class="small">(<?php echo $btc->high_eur ?> <i class="fa fa-eur"></i>)</span>
                                                    <small><i class="fa fa-clock-o"></i> <?php echo date("H:i", $btc->timestamp) ?></small>
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
                                                    <?php echo $btc->low ?> <i class="fa fa-dollar"></i> <span class="small">(<?php echo $btc->low_eur ?> <i class="fa fa-eur"></i>)</span>
                                                    <small><i class="fa fa-clock-o"></i> <?php echo date("H:i", $btc->timestamp) ?></small>
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
                                                    1 <i class="fa fa-eur"></i> == <?php echo $btc->eur_usd ?> <i class="fa fa-dollar"></i>
                                                    <small><i class="fa fa-clock-o"></i> <?php echo date("H:i", $btc->timestamp) ?></small>
                                                </h4>
                                                <p>Eur/Usd Rate</p>
                                            </a>
                                        </li>
                                    </ul>                                </li>
                                <li class="footer"><a href="https://www.bitstamp.net">Go to Bitstamp</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        
                       	<?php if ($appScript) : ?>
                        <!-- Altcoins Rates -->
						<li class="messages-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-money"></i> Altcoin prices
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">Data from Cryptsy</li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu altcoin-container" style="overflow: hidden; width: 100%; height: 200px;">
                                        <li>&nbsp;</li>
                                        
                                    </ul>
                                </li>
                                <li class="footer"><a href="https://www.cryptsy.com/users/register?refid=243592">Register at Cryptsy</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        
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
					                    <small>Made with heart</small>
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
										<a href="https://github.com/michelem09/minera" target="_blank">Github</a>
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
                            <p>Hello, your miner is</p>
							<?php if ($isOnline) : ?>
	                            <a href="#"><i class="fa fa-circle text-success"></i> Online <?php if ($minerdRunning) : ?><small class="pull-right badge bg-green"><?php echo $minerdRunning ?></small><?php endif; ?></a>
	                        <?php else: ?>
	                            <a href="#"><i class="fa fa-circle text-muted"></i> Offline <?php if ($minerdSoftware) : ?><small class="pull-right badge bg-muted"><?php echo $minerdSoftware ?></small><?php endif; ?></a>
							<?php endif; ?>
                        </div>
                    </div>

                    <!-- sidebar menu -->
                    <ul class="sidebar-menu">
                        <li data-toggle="tooltip" title="" data-original-title="Go to the dashboard page">
                            <a href="<?php echo site_url("app/dashboard") ?>">
                                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                            </a>
                        </li>
                        <li data-toggle="tooltip" title="" data-original-title="Go to the charts page">
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
                                	<a href="<?php echo site_url("app/settings#donation-box") ?>" style="margin-left: 10px;">
                                		<i class="fa fa-gift"></i> Donation
                                	</a>
                                </li>
                                <li>
                                	<a href="<?php echo site_url("app/settings#dashboard-box") ?>" style="margin-left: 10px;">
                                		<i class="fa fa-dashboard"></i> Dashboard
                                	</a>
                                </li>
                                <li>
                                	<a href="<?php echo site_url("app/settings#pools-box") ?>" style="margin-left: 10px;">
                                		<i class="fa fa-cloud"></i> Pools
                                	</a>
                                </li>
                                <li>
                                	<a href="<?php echo site_url("app/settings#customer-miners-box") ?>" style="margin-left: 10px;">
                                		<i class="fa fa-desktop"></i> Custom Miners
                                	</a>
                                </li>
                                <li>
                                	<a href="<?php echo site_url("app/settings#local-miner-box") ?>" style="margin-left: 10px;">
                                		<i class="fa fa-gear"></i> Local Miner
                                	</a>
                                </li>
                                <li>
                                	<a href="<?php echo site_url("app/settings#network-miners-box") ?>" style="margin-left: 10px;">
                                		<i class="fa fa-server"></i> Network Miners
                                	</a>
                                </li>
                                <li>
                                	<a href="<?php echo site_url("app/settings#top-bar-box") ?>" style="margin-left: 10px;">
                                		<i class="fa fa-money"></i> Top Bar
                                	</a>
                                </li>
                                <li>
                                	<a href="<?php echo site_url("app/settings#system-box") ?>" style="margin-left: 10px;">
                                		<i class="fa fa-rocket"></i> System
                                	</a>
                                </li>
                                <li>
                                	<a href="<?php echo site_url("app/settings#mobileminer-box") ?>" style="margin-left: 10px;">
                                		<i class="fa fa-mobile-phone"></i> Mobileminer
                                	</a>
                                </li>
                                <li>
                                	<a href="<?php echo site_url("app/settings#importexport-box") ?>" style="margin-left: 10px;">
                                		<i class="fa fa-code-fork"></i> Import/Export/Share
                                	</a>
                                </li>
                                <li>
                                	<a href="<?php echo site_url("app/settings#user-box") ?>" style="margin-left: 10px;">
                                		<i class="fa fa-user"></i> User
                                	</a>
                                </li>
                                <li>
                                	<a href="<?php echo site_url("app/settings#resets-box") ?>" style="margin-left: 10px;">
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
						<?php if ($sectionPage === "dashboard" && (($isOnline && $appScript) || count($netMiners) > 0)) : ?>
                        	<li data-toggle="tooltip" title="" data-original-title="Refresh Dashboard">
                            	<a href="#" class="refresh-btn">
                                	<i class="fa fa-refresh"></i> <span>Refresh</span><span class="badge bg-muted pull-right auto-refresh-time">auto in</span>
								</a>
							</li>
						<?php endif; ?>
                    </ul>
                </section>
                
                <!-- /.sidebar -->
            </aside>
            
