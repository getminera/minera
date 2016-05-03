			<!-- Right side column. Contains the navbar and content of the page -->
			<aside class="right-side">				   
				<!-- Content Header (Page header) -->
				<section class="content-header" data-toggle="dropdown">
					<h1>Mining <small>Dashboard</small></h1>
					<ol class="breadcrumb">
						<li><button class="btn btn-default btn-xs view-raw-stats"><i class="fa fa-list"></i> raw stats</button></li>
						<li><a href="<?php echo site_url("app/settings") ?>"><i class="fa fa-gear"></i> Settings</a></li>
					</ol>
				</section>

				<!-- Main content -->
				<section class="content">

					<div class="row" id="box-widgets">
						
						<?php if (count($netMiners) > 0) : ?>
						<section class="col-md-12 local-miners-title" style="display:none;">
							<h4>Local <small>Miner</small></h4>
						</section>
						<?php endif; ?>

						<section class="col-md-12 section-raw-stats">
							<div class="alert alert-info alert-dismissable">
								<i class="fa fa-list"></i>
								<button type="button" class="close close-stats" aria-hidden="true">×</button>
								<p style="margin:20px 0;">The raw JSON parsed to display the dashboard is also available <a href="<?php echo site_url("app/stats") ?>" target="_blank">here</a>.</p>
								<span></span>
							</div>
						</section>
							 						
						<?php if (isset($message)) : ?>
							 <section class="col-md-12 pop-message">
							 	<div class="alert alert-<?php echo $message_type ?> alert-dismissable">
									<i class="fa fa-check"></i>
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<?php echo $message ?>.
								</div>
							 </section>
						<?php endif; ?>
						<?php if ($this->session->flashdata('message')) : ?>
							 <section class="col-md-12 pop-message">
							 	<div class="alert alert-warning alert-dismissable">
									<i class="fa fa-check"></i>
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<?php echo $this->session->flashdata('message'); ?>.
								</div>
							 </section>
						<?php endif; ?>

						<!-- Warning section -->
						<section class="col-md-12 connectedSortable ui-sortable warning-section">
						
							<!-- Miner error -->
							<div class="box box-solid bg-red">
								<div class="box-header" style="cursor: move;">
									<!-- tools box -->
									<div class="pull-right box-tools">
										<button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
									</div><!-- /. tools -->
									<i class="fa fa-warning"></i>

									<h3 class="box-title">Warning!</h3>
								</div>
								<div class="box-body warning-message"></div>
								<div class="box-footer text-center">
									<a href="<?php site_url("app/dashboard") ?>"><h6>Click here to refresh</h6></a>
								</div>
							</div><!-- /.miner box -->	
						
						</section>
						
						<!-- widgets section -->
						<section class="col-md-12 widgets-section">
							<div class="row disable-if-not-running">
							 	<!-- total hashrate widget -->
								<div class="col-lg-4 col-sm-4 col-xs-12">
									<!-- small box -->
									<div class="small-box bg-red">
										<div class="inner">
											<h3 class="widget-total-hashrate"><i class="ion spin ion-load-c"></i></h3>
											<p>Pool Hashrate</p>
										</div>
										<div class="icon"><i class="ion ion-ios-speedometer-outline"></i></div>
										<a href="#hashrate-history" class="small-box-footer">History <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>
								
								<!-- hw/re widget -->
								<div class="col-lg-4 col-sm-4 col-xs-12">
									<!-- small box -->
									<div class="small-box bg-light">
										<div class="inner">
											<h3 class="widget-hwre-rates"><i class="ion spin ion-load-c"></i></h3>
											<p>Error/Rejected rates</p>
										</div>
										<div class="icon"><i class="ion ion-alert-circled"></i></div>
										<a href="#error-history" class="small-box-footer">Details <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>
								
								<!-- last share widget -->
								<div class="col-lg-4 col-sm-4 col-xs-12">
									<!-- small box -->
									<div class="small-box bg-light-blue">
										<div class="inner">
											<h3 class="widget-last-share"><i class="ion spin ion-load-c"></i></h3>
											<p>Last Share</p>
										</div>
										<div class="icon"><i class="ion ion-ios-stopwatch-outline"></i></div>
										<a href="#miner-details" class="small-box-footer">Miner details <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>
							</div>							
							
							<div class="row">

								<!-- Warning  widget -->
								<div class="col-lg-4 col-sm-4 col-xs-12 enable-if-not-running local-widget disable-if-stopped" style="display: none;">
									<!-- small box -->
									<div class="small-box bg-red">
										<div class="inner">
											<h3 class="widget-warning"><i class="ion spin ion-load-c"></i></h3>
											<p>Local miner</p>
										</div>
										<div class="icon"><i class="ion ion-alert"></i></div>
										<a href="" class="small-box-footer warning-message" data-toggle="tooltip" title="" data-original-title="Your local miner is offline, try to restart it. If you are in trouble check your logs and settings.">...<i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>
								
								<!-- Stopped  widget -->
								<?php if (!$this->redis->get("minerd_status")) : ?>
								<div class="col-lg-4 col-sm-4 col-xs-12 enable-if-not-running local-widget" style="display: none;">
									<!-- small box -->
									<div class="small-box bg-gray">
										<div class="inner">
											<h3 class="widget-warning">Offline</h3>
											<p>Local miner</p>
										</div>
										<div class="icon"><i class="ion ion-power"></i></div>
										<a href="#" data-miner-action="start" class="miner-action small-box-footer warning-message" data-toggle="tooltip" title="" data-original-title="Your local miner is offline, try to restart it.">Try to start it <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>
								<?php endif; ?>

								<!-- sys temp widget -->
								<div class="col-lg-4 col-sm-4 col-xs-12 local-widget">
									<!-- small box -->
									<div class="small-box sys-temp-box bg-blue">
										<div class="inner">
											<h3 class="widget-sys-temp"><i class="ion spin ion-load-c"></i></h3>
											<p>System temperature</p>
										</div>
										<div class="icon"><i class="ion ion-thermometer"></i></div>
										<a href="#sysload" class="small-box-footer sys-temp-footer">...<i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>
								
								<!-- main pool -->
								<div class="col-lg-4 col-sm-4 col-xs-12 disable-if-not-running">
									<!-- small box -->
									<div class="small-box bg-dark">
										<div class="inner">
											<h3 class="widget-main-pool"><i class="ion spin ion-load-c"></i></h3>
											<p>Checking...</p>
										</div>
										<div class="icon"><i class="ion ion-ios-cloud-upload-outline"></i></div>
										<a href="#pools-details" class="small-box-footer">Pools details <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>
								
								<!-- uptime widget -->
								<div class="col-lg-4 col-sm-4 col-xs-12 disable-if-not-running">
									<!-- small box -->
									<div class="small-box bg-aqua">
										<div class="inner">
											<h3 class="widget-uptime"><i class="ion spin ion-load-c"></i></h3>
											<p>Miner uptime</p>
										</div>
										<div class="icon"><i class="ion ion-ios-timer-outline"></i></div>
										<a href="#miner-details" class="small-box-footer uptime-footer">... <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>
								
							</div>
							
						</section>
						
						<!-- network widgets section -->
						<?php if (count($netMiners) > 0) : ?>
						<div class="network-miners-widget-section">
							<section class="col-md-12 local-miners-title">
								<h4>Network <small>Miners</small></h4>
							</section>
						</div>
						
						<section class="network-miners-widget-section col-md-12 widgets-section">
							<div class="row">
							 	<!-- total network hashrate widget -->
								<div class="col-lg-4 col-sm-4 col-xs-12">
									<!-- small box -->
									<div class="small-box bg-dark-blue">
										<div class="inner">
											<h3 class="network-widget-total-hashrate"><i class="ion spin ion-load-c"></i></h3>
											<p>Network Pool Hashrate</p>
										</div>
										<div class="icon"><i class="ion ion-ios-speedometer"></i></div>
										<a href="#network-pools-details" class="small-box-footer">Totals net devices <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>
								
								<!-- network hw/re widget -->
								<div class="col-lg-4 col-sm-4 col-xs-12">
									<!-- small box -->
									<div class="small-box bg-light2">
										<div class="inner">
											<h3 class="network-widget-hwre-rates"><i class="ion spin ion-load-c"></i></h3>
											<p>Network Error/Rejected Rates</p>
										</div>
										<div class="icon"><i class="ion ion-alert-circled"></i></div>
										<a href="#network-details" class="small-box-footer">Details <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>
								
								<!-- last network share widget -->
								<div class="col-lg-4 col-sm-4 col-xs-12">
									<!-- small box -->
									<div class="small-box bg-light-brown">
										<div class="inner">
											<h3 class="network-widget-last-share"><i class="ion spin ion-load-c"></i></h3>
											<p>Last Network Share</p>
										</div>
										<div class="icon"><i class="ion ion-ios-stopwatch-outline"></i></div>
										<a href="#network-details" class="small-box-footer">Network details <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>
							</div>							
						</section>
						<?php endif; ?>
						
						<!-- Top section -->
						<section class="hidden-xs col-md-12 connectedSortable ui-sortable top-section">
							<?php if ($dashboardBoxProfit) : ?>
							<!-- Profit box -->
							<div class="box box-light <?php if (isset($boxStatuses['box-profit']) && !$boxStatuses['box-profit']) :?>collapsed-box<?php endif; ?>" id="box-profit">
							   	<div class="overlay"></div>
							   	<div class="loading-img"></div>
								<div class="box-header" style="cursor: move;">
									<!-- tools box -->
									<div class="pull-right box-tools">
										<button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
									</div><!-- /. tools -->
									<i class="fa fa-line-chart"></i>

									<h3 class="box-title" id="miner-details">Mining profitability <small class="profit-whatmine"></small></h3>
								</div>
								<div class="box-body">
									<div class="row">
										<div class="col-sm-2">
											<div class="input-group">
								    			<span class="input-group-addon"><i class="fa fa-dashboard"></i></span>
								    			<input type="text" class="form-control profit_hashrate" placeholder="Hashrate" name="profit_hashrate" value="" />
								    		</div>
								    	</div>
										<div class="col-sm-2">
											<select name="profit_unit" class="form-control profit_data profit_unit">
												<option value="1000000000" data-profit-unit="PH/s">PH/s</option>
												<option value="1000000" data-profit-unit="TH/s">TH/s</option>
												<option value="1000" data-profit-unit="GH/s">GH/s</option>
												<option value="1" data-profit-unit="MH/s" selected>MH/s</option>
												<option value="0.001" data-profit-unit="KH/s">KH/s</option>
											</select>
										</div>
										<div class="col-sm-2">
											<select name="profit_period" class="form-control profit_data profit_period">
												<option value="0.0416" data-profit-period="Hour">Hour</option>
												<option value="1" data-profit-period="Day" selected>Day</option>
												<option value="7" data-profit-period="Week">Week</option>
												<option value="30" data-profit-period="Month">Month</option>
											</select>
										</div>
										<div class="col-sm-3 profit_algo" data-profit-algo="<?php echo strtolower(str_replace("-", "", $localAlgo)) ?>">
											<button class="btn btn-default profit_algo_scrypt active" data-algo="Scrypt">Scrypt</button>&nbsp;
											<button class="btn btn-default profit_algo_sha256" data-algo="SHA-256">SHA-256</button>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-6">
							    			<h6><i class="fa fa-btc"></i> Earnings column calculation data: <span class="label label-primary profit_local_hashrate"></span> <span class="label label-info profit_local_period">Day</span> <span class="label label-dark profit_local_algo"></span></h6>
								    	</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<div class="profit-table-details-error"></div>
											<div class="">
												  <table id="profit-table-details" class="responsive-datatable-minera table table-striped datatable">
													  <thead>
													  <tr>
														  <th><i class="fa fa-money"></i> Coin</th>
														  <th><i class="fa fa-bullseye"></i> Difficulty</th>
														  <th><i class="fa fa-trophy"></i> Reward</th>
														  <th><i class="fa fa-th"></i> Blocks</th>
														  <th><i class="fa fa-dashboard"></i> Hash Rate</th>
														  <th><i class="fa fa-exchange"></i> Exchange Rate</th>
														  <th><i class="fa fa-btc"></i> Earnings</th>
														  <th>BTC / 1MH</th>
														  <th>% / LTC</th>
														  <th>Coins / 1MH</th>
													  </tr>
													  </thead>
													  <tbody class="profit_table">
													</tbody>
													  <tfoot class="profit_table_foot">
													</tfoot>
												</table><!-- /.table -->
											  </div>
										</div>
									</div><!-- /.row - inside box -->
								</div><!-- /.box-body -->
								<div class="box-footer">
									<?php if (!$adsFree) : ?>
									<div class="pull-right">
										<?php echo $ads['234x60'] ?>
									</div>
									<?php endif; ?>
							 		<h6>Exchange rates taken by <a href="https://www.blockr.io">Blockr.io</a> are updated every 10 minutes</h6>
							 		<h6>Everything else are (almost) in real time. Profit formula is: <i>( time / (difficulty * 2^32) / hashrate ) * reward</i></h6>
								</div>
							</div><!-- /.profit box -->
							<?php endif; ?>
						
							<?php if ($dashboardBoxLocalMiner) : ?>
							<!-- Local Miner box -->
							<div class="box box-light <?php if (isset($boxStatuses['box-local-miner']) && !$boxStatuses['box-local-miner']) :?>collapsed-box<?php endif; ?>" id="box-local-miner">
							   	<div class="overlay"></div>
							   	<div class="loading-img"></div>
								<div class="box-header" style="cursor: move;">
									<!-- tools box -->
									<div class="pull-right box-tools">
										<?php if ($minerdRunning == "cpuminer") : ?><button class="btn btn-default btn-xs save-freq" data-toggle="tooltip" title="" data-original-title="Save current frequencies"><i class="fa fa-pencil"></i></button><?php endif; ?>
										<button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
									</div><!-- /. tools -->
									<i class="fa fa-desktop"></i>

									<h3 class="box-title" id="miner-details">Local Miner details <small><?php echo ($minerdRunning) ? '('.$minerdRunning.')' : ''; ?></small></h3>
								</div>
								<div class="box-body">
									<div class="row">
										<div class="col-sm-12">
											<div class="">
												  <table id="miner-table-details" class="responsive-datatable-minera table table-striped datatable">
													  <thead>
													  <tr>
														  <th>DEV</th>
														  <th>Temp</th>
														  <th>Frequency</th>
														  <th>Dev HR</th>
														  <th>Shares</th>
														  <th>AC</th>
														  <th>% AC</th>
														  <th>RE</th>
														  <th>% RE</th>
														  <th>HW</th>
														  <th>% HW</th>
														  <th>Last share</th>
														  <th>Last share time</th>
													  </tr>
													  </thead>
													  <tbody class="devs_table">
													</tbody>
													  <tfoot class="devs_table_foot">
													</tfoot>
												</table><!-- /.table -->
											  </div>
										</div>
									</div><!-- /.row - inside box -->
								</div><!-- /.box-body -->
								<div class="box-footer">
									<div class="legend pull-right">
								 		<h6>Colors based on last share time: <i class="fa fa-circle text-success"></i> Good&nbsp;&nbsp;&nbsp;<i class="fa fa-circle text-warning"></i> Warning&nbsp;&nbsp;&nbsp;<i class="fa fa-circle text-danger"></i> Critical&nbsp;&nbsp;&nbsp;<i class="fa fa-circle text-muted"></i> Possibly dead</h6>
								 	</div>
								 	<?php if ($savedFrequencies && $minerdRunning == "cpuminer") : ?>
								 	<button class="btn btn-primary btn-sm btn-saved-freq" data-toggle="tooltip" title="" data-original-title="Look at saved frequencies"><i class="fa fa-eye"></i> Saved frequencies</button>
								 	<?php else: ?>
								 	&nbsp;
								 	<?php endif; ?>
								 	<div class="freq-box" style="display:none; margin-top:10px;">
									  	<h6>You can find this on the <a href="<?php echo site_url("app/settings") ?>">settings page</a> too.</h6>
										<pre id="miner-freq" style="font-size:10px; margin-top:10px;">--gc3355-freq=<?php echo $savedFrequencies ?></pre>
								 	</div>
								</div>
							</div><!-- /.miner box -->
							<?php endif; ?>
							
							<?php if ($dashboardBoxLocalPools) : ?>
							<!-- Local Pools box -->
							<div class="box box-light <?php if (isset($boxStatuses['box-local-pools']) && !$boxStatuses['box-local-pools']) :?>collapsed-box<?php endif; ?>" id="box-local-pools">
							   	<div class="overlay"></div>
							   	<div class="loading-img"></div>
								<div class="box-header" style="cursor: move;">
									<!-- tools box -->
									<div class="pull-right box-tools">
										<button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
									</div><!-- /. tools -->
									<i class="fa fa-cloud"></i>

									<h3 class="box-title" id="pools-details">Local Pools details <small><?php echo ($minerdRunning) ? '('.$minerdRunning.')' : ''; ?></small></h3>
								</div>
								<div class="box-body">
									<div class="row">
										<div class="col-sm-12">
											<div class="">
												  <!-- .table - Uses sparkline charts-->
												  <table id="pools-table-details" class="responsive-datatable-minera table table-striped datatable">
													  <thead>
													  <tr>
														  <th>&nbsp;</th>
														  <th>Pool</th>
														  <th>Url</th>
														  <th>Type</th>
														  <th>Status</th>
														  <th>Pool HR</th>
														  <th>CS</th>
														  <th>PS</th>
														  <th>CA</th>
														  <th>PA</th>
														  <th>CR</th>
														  <th>PR</th>
														  <th>Username</th>
													  </tr>
													  </thead>
													  <tbody class="pools_table">
													</tbody>
												</table><!-- /.table -->
												<div class="pools-addbox">
													<button class="btn btn-xs btn-primary toggle-add-pool" data-open="0"><i class="fa fa-plus"></i> Add pool</button>
													<div class="form-group mt10" style="display:none;">
														<?php if ($minerdRunning != 'cpuminer') : ?>
															<div class="row sort-attach">
														    	<div class="col-xs-5">
														    		<div class="input-group">
														    			<span class="input-group-addon"><i class="fa fa-cloud-download"></i></span>
														    			<input type="text" class="form-control local_pool_url" placeholder="Pool url" name="local_pool_url" value="" />
														    		</div>
														    	</div>
														    	<div class="col-xs-3">
														    		<div class="input-group">
														    			<span class="input-group-addon"><i class="fa fa-user"></i></span>
														    			<input type="text" class="form-control local_pool_username" placeholder="username" name="local_pool_username" value=""  />
														    		</div>
														    	</div>
														    	<div class="col-xs-3">
														    		<div class="input-group">
														    			<span class="input-group-addon"><i class="fa fa-lock"></i></span>
														    			<input type="text" class="form-control local_pool_password" placeholder="password" name="local_pool_password" value=""  />
														    		</div>
														    	</div>
														    	<div class="col-xs-1">
														    		<div class="input-group">
														    			<button class="btn btn-sm btn-success add-pool"><i class="fa fa-plus"></i> Add</button>
														    		</div>
														    	</div>
														    </div>
													    <?php else : ?>
															<p class="well">Sorry but CPUMiner doesn't support add/remove pools on the fly, try another miner software.</p>
														<?php endif; ?>
													</div>
												</div>
												<p class="pool-alert"></p>
											  </div>
										</div>
									</div><!-- /.row - inside box -->
								</div><!-- /.box-body -->
								<div class="box-footer">
									<?php if (!$adsFree) : ?>
									<div class="pull-right">
										<?php echo $ads['234x60'] ?>
									</div>
									<?php endif; ?>
									<h6>Legend: <strong>CS</strong> = Current Shares, <strong>PS</strong> = Previous shares, <strong>CA</strong> = Current Accepted, <strong>PA</strong> = Previous Accepted, <strong>CR</strong> = Current Rejected, <strong>PR</strong> = Previous Rejected</h6>
									<h6><strong>Current</strong> is the current or last session, <strong>Previous</strong> is the total of all previous sessions. Pool HashRate is based on shares over the time per session.</h6>
								</div>
							</div><!-- /.local pools box -->
							<?php endif; ?>
							
							<?php if ($dashboardBoxNetworkDetails) : ?>
								<!-- Network Miners box -->
								<?php if (count($netMiners) > 0) : ?>
								<div id="box-network-details" class="box box-light network-miner-details <?php if (isset($boxStatuses['box-network-details']) && !$boxStatuses['box-network-details']) :?>collapsed-box<?php endif; ?>" style="display:none;">
								   	<div class="overlay"></div>
								   	<div class="loading-img"></div>
									<div class="box-header" style="cursor: move;">
										<!-- tools box -->
										<div class="pull-right box-tools">
											<button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
										</div><!-- /. tools -->
										<i class="fa fa-server"></i>
	
										<h3 class="box-title" id="miner-details">Network Miners details</h3>
									</div>
									<div class="box-body">
										<div class="row">
											<div class="col-sm-12">
												<div class="">
													  <table id="network-miner-table-details" class="responsive-datatable-minera table table-striped datatable">
														  <thead>
														  <tr>
															  <th>DEV</th>
															  <th>Temp</th>
															  <th>Frequency</th>
															  <th>Dev HR</th>
															  <th>Shares</th>
															  <th>AC</th>
															  <th>% AC</th>
															  <th>RE</th>
															  <th>% RE</th>
															  <th>HW</th>
															  <th>% HW</th>
															  <th>Last share</th>
															  <th>Last share time</th>
														  </tr>
														  </thead>
														  <tbody class="network_devs_table">
														</tbody>
														  <tfoot class="network_devs_table_foot">
														</tfoot>
													</table><!-- /.table -->
												  </div>
											</div>
										</div><!-- /.row - inside box -->
									</div><!-- /.box-body -->
									<div class="box-footer">
										<div class="legend pull-right">
									 		<h6>Colors based on last share time: <i class="fa fa-circle text-success"></i> Good&nbsp;&nbsp;&nbsp;<i class="fa fa-circle text-warning"></i> Warning&nbsp;&nbsp;&nbsp;<i class="fa fa-circle text-danger"></i> Critical&nbsp;&nbsp;&nbsp;<i class="fa fa-circle text-muted"></i> Possibly dead</h6>
										</div>
										<div>&nbsp;</div>
									</div>
								</div><!-- /.network miner box -->
								<?php endif; ?>
							<?php endif; ?>	
							<?php if ($dashboardBoxNetworkPoolsDetails) : ?>
								<!-- Network pools box -->
								<?php if (count($netMiners) > 0) : ?>
								<div id="box-network-pools-details" class="box box-light <?php if (isset($boxStatuses['box-network-pools-details']) && !$boxStatuses['box-network-pools-details']) :?>collapsed-box<?php endif; ?>">
								   	<div class="overlay"></div>
								   	<div class="loading-img"></div>
									<div class="box-header" style="cursor: move;">
										<!-- tools box -->
										<div class="pull-right box-tools">
											<button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
										</div><!-- /. tools -->
										<i class="fa fa-cloud"></i>
	
										<h3 class="box-title" id="pools-details" >Network Pools details</h3>
									</div>
									<div class="box-body">
										<div class="row">
											<div class="col-sm-12">
												<?php $npi = 1; $netCounts = count($netMiners); ?>
													<?php foreach ($netMiners as $netMiner) : ?>
													<hr />
													<div id="net-<?php echo md5($netMiner->name) ?>">
														<div class="mb20 net-pools-label-<?php echo md5($netMiner->name) ?>"></div>
														<div class="">
															  <!-- .table - Uses sparkline charts-->
															  <table id="net-pools-table-details-<?php echo md5($netMiner->name) ?>" class="responsive-datatable-minera net-pools-table table table-striped datatable">
																  <thead>
																  <tr>
																	  <th>&nbsp;</th>
																	  <th>Pool</th>
																	  <th>Url</th>
																	  <th>Type</th>
																	  <th>Status</th>
																	  <th>Pool HR</th>
																	  <th>CS</th>
																	  <th>PS</th>
																	  <th>CA</th>
																	  <th>PA</th>
																	  <th>CR</th>
																	  <th>PR</th>
																	  <th>Username</th>
																  </tr>
																  </thead>
																  <tbody class="net_pools_table">
																</tbody>
															</table><!-- /.table -->
															<p class="net-pool-alert-<?php echo md5($netMiner->name) ?>"></p>
														</div>
														<div class="net-pools-addbox-<?php echo md5($netMiner->name) ?>">
															<button class="btn btn-xs btn-primary toggle-add-net-pool" data-open="0"><i class="fa fa-plus"></i> Add pool</button> <button class="btn btn-xs btn-danger add-net-donation-pool" data-netminer="<?php echo md5($netMiner->name) ?>" data-network="<?php echo $netMiner->ip.':'.$netMiner->port ?>" data-netcoin="<?php echo $netMiner->algo ?>"><i class="fa fa-gift"></i> Add donation pool</button>
															<div class="form-group mt10" style="display:none;">
																<div class="row sort-attach">
															    	<div class="col-xs-5">
															    		<div class="input-group">
															    			<span class="input-group-addon"><i class="fa fa-cloud-download"></i></span>
															    			<input type="text" class="form-control pool_url_<?php echo md5($netMiner->name) ?>" placeholder="Pool url" name="pool_url" value="" />
															    		</div>
															    	</div>
															    	<div class="col-xs-3">
															    		<div class="input-group">
															    			<span class="input-group-addon"><i class="fa fa-user"></i></span>
															    			<input type="text" class="form-control pool_username_<?php echo md5($netMiner->name) ?>" placeholder="username" name="pool_username" value=""  />
															    		</div>
															    	</div>
															    	<div class="col-xs-3">
															    		<div class="input-group">
															    			<span class="input-group-addon"><i class="fa fa-lock"></i></span>
															    			<input type="text" class="form-control pool_password_<?php echo md5($netMiner->name) ?>" placeholder="password" name="pool_password" value=""  />
															    		</div>
															    	</div>
															    	<div class="col-xs-1">
															    		<div class="input-group">
															    			<button class="btn btn-sm btn-success add-net-pool" data-netminer="<?php echo md5($netMiner->name) ?>" data-network="<?php echo $netMiner->ip.':'.$netMiner->port ?>"><i class="fa fa-plus"></i> Add</button>
															    		</div>
															    	</div>
															    </div>
															</div>
														</div>
														<div class="net-pool-error-<?php echo md5($netMiner->name) ?> mt10 text-red"></div>
													</div>
												<?php $npi++; endforeach; ?>
											</div>
										</div><!-- /.row - inside box -->
									</div><!-- /.box-body -->
									<div class="box-footer">
										<h6>Every changes here will be lost if you stop/restart your network miner</h6>
										<h6>Legend: <strong>CS</strong> = Current Shares, <strong>PS</strong> = Previous shares, <strong>CA</strong> = Current Accepted, <strong>PA</strong> = Previous Accepted, <strong>CR</strong> = Current Rejected, <strong>PR</strong> = Previous Rejected</h6>
										<h6><strong>Current</strong> is the current or last session, <strong>Previous</strong> is the total of all previous sessions. Pool HashRate is based on shares over the time per session.</h6>
									</div>
								</div><!-- /.network pools box -->
								<?php endif; ?>
							<?php endif; ?>
						</section>
						
						<!-- Right col -->
						<section class="col-md-6 col-xs-12 connectedSortable ui-sortable right-section" id="box-charts">
							<?php if ($dashboardBoxChartShares) : ?>
							<!-- A/R/H chart -->
							<div class="box box-primary <?php if (isset($boxStatuses['box-chart-shares']) && !$boxStatuses['box-chart-shares']) :?>collapsed-box<?php endif; ?>" id="box-chart-shares">
							   	<div class="overlay"></div>
							   	<div class="loading-img"></div>
								<div class="box-header">
									<!-- tools box -->
									<div class="pull-right box-tools">
										<button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
									</div><!-- /. tools -->
									<i class="fa fa-bullseye"></i>
									
									<h3 class="box-title" id="error-history">Local Accepted/Rejected/Errors</h3>
								</div>
								<div class="box-body chart-responsive">
									<div class="chart" id="rehw-chart" style="height:160px;"></div>
								</div>
							</div><!-- /.A/R/H chart -->
							<?php endif; ?>
							
							<?php if ($dashboardBoxChartSystemLoad) : ?>
							<!-- System box -->
							<div class="box box-light <?php if (isset($boxStatuses['box-chart-system-load']) && !$boxStatuses['box-chart-system-load']) :?>collapsed-box<?php endif; ?>" id="box-chart-system-load">
							   	<div class="overlay"></div>
							   	<div class="loading-img"></div>
								<div class="box-header" style="cursor: move;">
									<!-- tools box -->
									<div class="pull-right box-tools">
										<button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
									</div><!-- /. tools -->
									<i class="fa fa-tasks"></i>

									<h3 class="box-title" id="sysload">System Load</h3>
								</div><!-- /.box-header -->
								<div class="box-body" style="display: block;">
									<div class="row padding-vert sysload" ></div>
								</div><!-- /.box-body -->
								<div class="box-footer">
									<h6 class="sysuptime"></h6>
							   </div>
							</div><!-- /.system box -->
							<?php endif; ?>
												 
						</section><!-- Right col -->
						
						<!-- Left col -->
						<section class="col-md-6 col-xs-12 connectedSortable ui-sortable left-section">
							
							<?php if ($dashboardBoxChartHashrates) : ?>
							<!-- Hashrate box chart -->
							<div class="box box-primary <?php if (isset($boxStatuses['box-chart-hashrates']) && !$boxStatuses['box-chart-hashrates']) :?>collapsed-box<?php endif; ?>" id="box-chart-hashrates">
							   	<div class="overlay"></div>
							   	<div class="loading-img"></div>
								<div class="box-header">
									<!-- tools box -->
									<div class="pull-right box-tools">
										<button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
									</div><!-- /. tools -->
									<i class="fa fa-bar-chart-o"></i>
									
									<h3 class="box-title" id="hashrate-history">Local Hashrate History</h3>
								</div>
								<div class="box-body chart-responsive">
									<div class="chart" id="hashrate-chart" style="height:160px;"></div>
								</div>
							</div><!-- /.hashrate box -->
							<?php endif; ?>
							
							<?php if ($dashboardBoxScryptEarnings) : ?>
							<!-- Profitability box -->
							<div class="box box-dark <?php if (isset($boxStatuses['box-scrypt-earnings']) && !$boxStatuses['box-scrypt-earnings']) :?>collapsed-box<?php endif; ?>" id="box-scrypt-earnings">
								<div class="box-header" style="cursor: move;">
									<!-- tools box -->
									<div class="pull-right box-tools">
										<button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
									</div><!-- /. tools -->
									<i class="fa fa-signal"></i>

									<h3 class="box-title">Scrypt Earnings calculator</h3>
								</div><!-- /.box-header -->
								<div class="box-body" style="display: block;">
									<div class="profitability-box">
										<p>Pool profitability in <i class="fa fa-btc"></i>/Day per MH/s <a href="#" class="profitability-question"><small class="badge bg-light"><small><i class="fa fa-question"></i></small></small></a></p>
										<div class="callout callout-grey profitability-help" style="display:none;">
											<p><small>If you know the profitability of your pool you can select it sliding the bar to get your possible earnings based on your current pool hashrate. Profitability is usually expressed as <i class="fa fa-btc"></i> per day per MH/s. You can see for example the <a href="http://www.clevermining.com/profits/30-days" target="_blank">Clevermining one, here</a>.</small></p>
										</div>
										<div class="margin-bottom">
											<input type="text" name="default_profitability" id="profitability-slider" value="" />
										</div>
									</div>
								</div><!-- /.box-body -->
								<div class="box-footer">
									<div class="profitability-results"><small>Drag and slide the bar above to set your pool profitability and calculate your current possible earnings.</small></div>
								</div>
							</div><!-- /.tree box -->
							<?php endif; ?>
						
						</section><!-- /.left col -->
						
					</div><!-- /.row -->
					
					<div class="row">
					
						<!-- Bottom section -->
						<section class="col-md-12 connectedSortable ui-sortable bottom-section">
														
							<?php if ($dashboardDevicetree) : ?>
							<!-- Tree box -->
							<div class="box box-dark <?php if (isset($boxStatuses['box-device-tree']) && !$boxStatuses['box-device-tree']) :?>collapsed-box<?php endif; ?>" id="box-device-tree">
							   	<div class="overlay"></div>
							   	<div class="loading-img"></div>
								<div class="box-header" style="cursor: move;">
									<!-- tools box -->
									<div class="pull-right box-tools">
										<button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
									</div><!-- /. tools -->
									<i class="fa fa-sitemap"></i>

									<h3 class="box-title">Device Tree</h3>
								</div><!-- /.box-header -->
								<div class="box-body" style="display: block;">
									<div class="row padding-vert" id="devs-total" ></div>
									<div class="row padding-vert" id="devs"></div>
								</div><!-- /.box-body -->
							</div><!-- /.tree box -->
							<?php endif; ?>
						
							<?php if ($dashboardBoxLog) : ?>
							<!-- Real time log box -->
							<div class="box box-light <?php if (isset($boxStatuses['box-log']) && !$boxStatuses['box-log']) :?>collapsed-box<?php endif; ?>" id="box-log">
								<div class="box-header" style="cursor: move;">
									<!-- tools box -->
									<div class="pull-right box-tools">
										<a href="<?php echo base_url($this->config->item("minerd_log_url")); ?>" target="_blank" style="padding-right: 20px;"><button class="btn btn-default btn-xs"><i class="fa fa-briefcase"></i> view raw log</button></a>
										<button class="btn btn-default btn-xs pause-log" data-widget="pause" data-toggle="tooltip" title="" data-original-title="Pause Log"><i class="fa fa-pause"></i></button>
										<button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
									</div><!-- /. tools -->
									<i class="fa fa-file-o"></i>

									<h3 class="box-title" id="pools-details">Miner real time log</h3>
								</div>
								<div class="box-body">
									<?php if ($minerdLog) :?>
										<pre class="log-box" id="real-time-log-data">Logger is in pause, click play to resume it.</pre>
									<?php else: ?>
										<pre>Please enable logging in the settings page to see the miner log here.</pre>
									<?php endif; ?>
								</div><!-- /.box-body -->
								<div class="box-footer">
									<h6>To download the full <a href="<?php echo base_url($this->config->item("minerd_log_url")); ?>" target="_blank">raw log please click this link</a>.</h6>
								</div>
							</div><!-- /.miner box -->
							<?php endif; ?>
							
							<?php if (!$this->redis->get("minera_donation_time")) : ?>					
							<!-- Donations box -->
							<div class="box bg-light box-danger" id="box-donation">
								<div class="box-header">
									<!-- tools box -->
									<i class="fa fa-gift"></i>

									<h3 class="box-title">Donations</h3>
								</div>
								<div class="box-body text-center">
		                        	<div class="coinbase-donate-button">
		                            	<a class="coinbase-button" data-code="01ce206aaaf1a8659b07233d9705b9e8" data-button-style="custom_small" href="https://www.coinbase.com/checkouts/01ce206aaaf1a8659b07233d9705b9e8">Donate Bitcoins</a>
									</div>
									<p class="more-line-height">If you like Minera, please consider a donation to support it. <strong>Bitcoin</strong>: <code><a href="bitcoin:1AmREReHNLec9EaW7gLRdW31LNSDA9SGR1" target="_blank">1AmREReHNLec9EaW7gLRdW31LNSDA9SGR1</a></code> <strong>Litecoin</strong>: <code><a href="litecoin:LLPmAT9gDwmiSdqwWEZu6mpUDmrNAnYBdC" target="_blank">LLPmAT9gDwmiSdqwWEZu6mpUDmrNAnYBdC</a></code> <strong>Dogecoin</strong>: <code><a href="dogecoin:DLAHwNxfUTUcePewbkvwvAouny19mcosA7" target="_blank">DLAHwNxfUTUcePewbkvwvAouny19mcosA7</a></code></p>
								</div><!-- /.box-body -->
							</div>
							<?php endif; ?>
						</section>
					</div>

				</section><!-- /.content -->
			</aside><!-- /.right-side -->
		</div><!-- ./wrapper -->