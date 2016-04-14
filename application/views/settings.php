    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side ">                	
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Mining
                <small>Settings</small>
            </h1>
            <ul class="mini-save-toolbox">
				<li>
					<button type="submit" class="btn btn-sm btn-primary save-minera-settings" name="save" value="1"><i class="fa fa-floppy-o"></i> Save</button>
				</li>
				<li>
					<button type="submit" class="btn btn-sm btn-danger save-minera-settings-restart" name="save_restart" value="1"><i class="fa fa-repeat"></i> Save & Restart Miner</button>
				</li>
	    	</ul>
            <ol class="breadcrumb">
                <li><a href="<?php echo site_url("app/dashboard") ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            </ol>
        </section>

		<!-- Save toolbox -->
    	<div class="save-toolbox">
	    	<ul>
		    	<li><a href="#" class="toggle-save-toolbox"><i class="fa fa-close"></i></a></li>
				<li>
					<button type="submit" class="btn btn-lg btn-primary save-minera-settings" name="save" value="1"><i class="fa fa-floppy-o"></i> Save</button>
				</li>
				<li>
					<button type="submit" class="btn btn-lg btn-danger save-minera-settings-restart" name="save_restart" value="1"><i class="fa fa-repeat"></i> Save & Restart Miner</button>
				</li>
	    	</ul>
		</div>

        <!-- Main content -->
        <section class="content">

			<div class="row">

                <?php if ($message) : ?>
                    <section class="col-md-12">
                    	<div class="alert alert-<?php echo $message_type ?> alert-dismissable">
							<i class="fa fa-check"></i>
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<?php echo $message ?>.
						</div>
                    </section>
                <?php endif; ?>                        
                <?php if ($this->session->flashdata('message')) : ?>
                    <section class="col-md-12">
                    	<div class="alert alert-<?php echo $this->session->flashdata('message_type') ?> alert-dismissable">
							<i class="fa fa-check"></i>
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<?php echo $this->session->flashdata('message') ?>.
						</div>
                    </section>
                <?php endif; ?>                        
                
                <!-- Top section -->
                <section class="col-md-12">
						
					<form action="#" method="post" role="form" id="minersettings" enctype="multipart/form-data">
												
						<input type="hidden" name="save_settings" value="1" />                                                    

						<div class="row">
							<section class="left-section col-xs-12 col-md-6">
							
								<!-- Donation box -->
								<div class="box box-primary" id="donation-box">
								    <div class="box-header">
								    	<!-- tools box -->
								        <div class="pull-right box-tools">
								            <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
								        </div><!-- /. tools -->
								        <i class="fa fa-gift"></i>
								        
								        <h3 class="box-title">Donation Settings</h3>
								    </div>
								
								    <div class="box-body">
								    
								    	<!-- Donation time -->
								    	<div class="form-group">
								    		<label>Support Minera!</label>
								    		<p>Minera needs your help! Give some minutes of your hash power to the project and help making Minera even cooler!<br /><a href="#" class="open-readme-donation">Please read how you can give your support and how it works.</a></p>
								    		
								    		<div class="margin-bottom">
								    		    <input data-donation-profitability="<?php echo $donationProfitability ?>" type="text" name="minera_donation_time" id="option-minera-donation-time" value="" data-saved-donation-time="<?php echo $mineraDonationTime; ?>" />
								    		</div>
								    		
								    		<p class="donation-worth"></p>
								    		
								    		<p><small class="donation-mood badge">&nbsp;</small></p>
								    		
								    		<div class="callout callout-grey readme-donation" style="display:none;">
								    		    <h6><strong>How does time donation work?</strong></h6>

								    		    <p><small>It's really simple, you can select how many minutes per day you want to donate to Minera, you can choose between 10 minutes and 6 hours. If you leave it at zero minutes, donation will be disabled. If you enable it, every day (at about 4.10am system time) Minera will automatically switch to the donation pool for the amount of time you have selected. When that period is over Minera will switch back to your main pool. Pool changes are on the fly, so it doesn't need to save/restart anything.</small></p>

								    		    <h6><strong>What does happen if Minera reboot/shutdown/stop during the donation period?</strong></h6>
								    		    <p><small>Absolutely nothing. I mean, if your Minera system got problems while is running on the donation pool (for example it reboots as a result of a power failure), it just goes back to the main pool. It won't retry to switch the donation pool until the next day.</small></p>
								    		    <h6><strong>How do you calculate the amount donated?</strong></h6>
								    		    <p><small>It's just an approximate. I simply divide the time in minutes you donate per fixed average profitability of <?php echo $donationProfitability ?> <i class="fa fa-btc"></i>/Day, the function is: <i><?php echo $donationProfitability ?> / 24 / 60 x donation_minutes</i>.</small></p>
								    		    
								    		    <h6><strong>Is there a prize for who donate?</strong></h6>
								    		    <p><small>Well, my appreciation first of all, then yes you'll get a tiny prize, donation boxes on dashboard and sidebar disappears if you have donations active.</small></p>
								    		    
								    		    <h6><strong>What do you do with the money received?</strong></h6>
								    		    <p><small>Thanks to your kind donations I could buy some of the new hardware coming out and add its support to Minera. So next time there is a new cool hardware, Minera will be ready for it. I also need beer to do all this cool stuff :)</small></p>
								    		    <h6><strong>Anyway, I wanna really thank you for all your support and appreciation!</strong></h6>
								    		    <h6>- <a href="https://twitter.com/michelem" target="_blank"><strong>Michelem</strong></a></h6>
								    		</div>
								    		
								    		<?php if ($mineraStoredDonations) : ?>
								    		    <div class="stored-donations">
								    		    	<label><a href="#" class="view-stored-donations">View your past donations</a></label>
								    		    	<div class="table-responsive">
								    		    		<table id="stored-donation-table" class="table table-striped datatable" style="display:none;">
								    		    			<thead>
								    		    				<tr>
								    		    					<th>Date</th>
								    		    					<th>Period</th>
								    		    					<th>Hashrate</th>
								    		    					<th>Share</th>
								    		    				</tr>
								    		    			</thead>
								    		    			<tbody>
								    		    			<?php foreach ($mineraStoredDonations as $storedDonation) : ?>
								    		    				<tr>
								    		    				<?php list($donationDate, $donationPeriod, $donationHr) = explode(":", $storedDonation); ?>
								    		    				<td>
								    		    					<small><?php echo date("r", $donationDate) ?></small>
								    		    				</td>
								    		    				<td>
								    		    					<span class="label bg-blue"><?php echo $donationPeriod ?> minutes</span>
								    		    				</td>
								    		    				<td>
								    		    					<span class="label bg-green"><?php echo $this->util_model->convertHashrate($donationHr) ?></span>
								    		    				</td>
								    		    				<td><a href="https://twitter.com/home?status=<?php echo urlencode("@michelem I just donated ".$donationPeriod." minutes of my hash power to the #Minera project, your next #mining dashboard http://getminera.com #bitcoin") ?>" target="_blank" title="Tweet it!"><small class="badge bg-light-blue"><i class="fa fa-twitter"></i> Tweet It!</small></a></td>
								    		    				</tr>
								    		    			<?php endforeach; ?>
								    		    			</tbody>
								    		    			<tfoot>
								    		    			</tfoot>
								    		    		</table>
								    		    	</div>
								    		    </div>
								    		<?php endif; ?>
								    	</div>										
								    </div>
								</div>
                        
							</section><!-- End left section -->
							
							<section class="right-section col-xs-12 col-md-6">			
													
								<!-- Donations box -->
								<div class="box bg-light box-danger" id="box-donation">
									<div class="box-header">
										<!-- tools box -->
										<i class="fa fa-gift"></i>
			
										<h3 class="box-title">Donations</h3>
									</div>
									<div class="box-body text-center">
										<?php if (!$adsFree) : ?>
											<p>If you like Minera, please consider a donation to support it. To remove all the ads forever (for this system) please click the button below and complete the donation (cost: 0.01 <i class="fa fa-btc"></i> for one system for life).</p>
											<?php if ($env === 'development') : ?>
												<p><a class="coinbase-button" data-env="sandbox" data-code="0897e9510eba42b39d4a4a3e6a4742df" data-button-style="custom_large" data-button-text="Remove Ads" data-width="185" data-heigth="60" href="https://sandbox.coinbase.com/checkouts/0897e9510eba42b39d4a4a3e6a4742df" data-custom="<?php echo $mineraSystemId.'||removeads||'.site_url('app/dashboard') ?>">Remove Ads</a></p>
											<?php else : ?>
												<p><a class="coinbase-button" data-code="ee38d16e2e37e5f148153a8817d5dc27" data-button-style="custom_large" data-button-text="Remove Ads" data-width="185" data-heigth="60" href="https://sandbox.coinbase.com/checkouts/ee38d16e2e37e5f148153a8817d5dc27" data-custom="<?php echo $mineraSystemId.'||removeads||'.site_url('app/dashboard') ?>">Remove Ads</a></p>
											<?php endif; ?>
										<?php else : ?>
											<p>You are ads-free, Thanks!</p>
			                            	<a class="coinbase-button" data-code="01ce206aaaf1a8659b07233d9705b9e8" data-button-style="custom_large" data-width="210" data-heigth="60" href="https://www.coinbase.com/checkouts/01ce206aaaf1a8659b07233d9705b9e8">Donate Bitcoins</a>
										<?php endif; ?>
										<p><strong>Bitcoin</strong>: <code><a href="bitcoin:1AmREReHNLec9EaW7gLRdW31LNSDA9SGR1" target="_blank">1AmREReHNLec9EaW7gLRdW31LNSDA9SGR1</a></code></p>
										<p><strong>Litecoin</strong>: <code><a href="litecoin:LLPmAT9gDwmiSdqwWEZu6mpUDmrNAnYBdC" target="_blank">LLPmAT9gDwmiSdqwWEZu6mpUDmrNAnYBdC</a></code></p>
										<p><strong>Dogecoin</strong>: <code><a href="dogecoin:DLAHwNxfUTUcePewbkvwvAouny19mcosA7" target="_blank">DLAHwNxfUTUcePewbkvwvAouny19mcosA7</a></code></p>
									</div><!-- /.box-body -->
									<?php if (!$adsFree) : ?>
									<div class="box-footer text-center" style="clear:both">
								    	<iframe scrolling="no" style="border: 0; width: 234px; height: 60px;" src="//coinurl.com/get.php?id=49615"></iframe>
								    </div>
								    <?php endif; ?>
								</div>                            
							</section><!-- End right section -->

						</div><!-- End row -->
						
						<div class="row">
							<section class="left-section col-xs-12 col-md-6">
						
								<!-- Dashboard box -->
								<div class="box box-primary" id="dashboard-box">
									<div class="box-header">
										<!-- tools box -->
		                                <div class="pull-right box-tools">
		                                    <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
		                                </div><!-- /. tools -->
		                                <i class="fa fa-dashboard"></i>
		                                
		                                <h3 class="box-title">Dashboard Settings</h3>
		                            </div>
									
		                            <div class="box-body">
			                            <div class="row">
				                            <div class="col-md-12">
												<!-- Temperatures scale F°/C°-->
												<div class="form-group">
													<label>Temperature units</label>
													<p>Select your preferred units to display temperature.</p>
													<div class="radio">
														<label>
															<input type="radio" name="dashboard_temp" value="c" <?php if ($dashboardTemp == "c") : ?>checked=""<?php endif; ?> />
															Celsius (C°)
														</label>                                                
														<label>
															<input type="radio" name="dashboard_temp" value="f" <?php if ($dashboardTemp == "f") : ?>checked=""<?php endif; ?> />
															Fahrenheit (F°)
														</label>                                                
													</div>
												</div>
				
												<!-- Refresh time -->
												<div class="form-group">
													<label>Refresh time</label>
													<p>Select automatic refresh time interval.</p>
													<div class="margin-bottom">
														<input type="text" name="dashboard_refresh_time" id="option-dashboard-refresh-time" class="refresh-time" value="" data-saved-refresh-time="<?php echo (isset($dashboard_refresh_time)) ? $dashboard_refresh_time : 60; ?>" />
													</div>
												</div>
												
												<!-- Skin colors -->
												<div class="form-group">
													<label>Skin</label>
													<p>Select your favorite skin for your controller.</p>
													<select name="dashboard_skin" id="dashboard-skin" class="form-control">
														<option value="black" <?php if ($dashboardSkin == "black") : ?>selected<?php endif; ?>>Black</option>
														<option value="blue" <?php if ($dashboardSkin == "blue") : ?>selected<?php endif; ?>>Blue</option>
													</select>
												</div>
												
												<!-- Records per page -->
												<div class="form-group">
													<label>Data tables</label>
													<p>Default records per page</p>
													<select name="dashboard_table_records" id="dashboard-table-records" class="form-control">
														<option value="5" <?php if ($dashboardTableRecords == "5") : ?>selected<?php endif; ?>>5</option>
														<option value="10" <?php if ($dashboardTableRecords == "10") : ?>selected<?php endif; ?>>10</option>
														<option value="25" <?php if ($dashboardTableRecords == "25") : ?>selected<?php endif; ?>>25</option>
														<option value="50" <?php if ($dashboardTableRecords == "50") : ?>selected<?php endif; ?>>50</option>
													</select>
												</div>
				                            </div>
			                            </div>
		                            </div>
		                        </div>
							</section>
						
							<section class="right-section col-xs-12 col-md-6">			
													
								<!-- Panels box -->
								<div class="box box-primary" id="top-bar-box">
								    <div class="box-header">
								    	<!-- tools box -->
			                            <div class="pull-right box-tools">
			                                <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
			                            </div><!-- /. tools -->
			                            <i class="fa fa-map-signs"></i>
			                            
			                            <h3 class="box-title">Dashboard panels</h3>
			                        </div>
								    
			                        <div class="box-body">								    		
							    		<!-- Local device tree -->
										<div class="form-group">
											<label>Section panels</label>
											<p>Select what section you want enable/disable in the dashboard.</p>
											<table class="box-panels">
												<tr>
													<td><input type="checkbox" name="dashboard_box_profit" value="1" <?php if ($dashboardBoxProfit) : ?>checked=""<?php endif; ?> /> Mining profitability</td>
													<td><input type="checkbox" name="dashboard_box_local_miner" value="1" <?php if ($dashboardBoxLocalMiner) : ?>checked=""<?php endif; ?> /> Local miner</td>
												</tr>
												<tr>
													<td><input type="checkbox" name="dashboard_box_local_pools" value="1" <?php if ($dashboardBoxLocalPools) : ?>checked=""<?php endif; ?> /> Local pools</td>
													<td><input type="checkbox" name="dashboard_box_network_details" value="1" <?php if ($dashboardBoxNetworkDetails) : ?>checked=""<?php endif; ?> /> Network miners</td>
												</tr>
												<tr>
													<td><input type="checkbox" name="dashboard_box_network_pools_details" value="1" <?php if ($dashboardBoxNetworkPoolsDetails) : ?>checked=""<?php endif; ?> /> Network pools</td>
													<td><input type="checkbox" name="dashboard_box_chart_shares" value="1" <?php if ($dashboardBoxChartShares) : ?>checked=""<?php endif; ?> /> Shares chart</td>
												</tr>
												<tr>
													<td><input type="checkbox" name="dashboard_box_chart_system_load" value="1" <?php if ($dashboardBoxChartSystemLoad) : ?>checked=""<?php endif; ?> /> System load</td>
													<td><input type="checkbox" name="dashboard_box_chart_hashrates" value="1" <?php if ($dashboardBoxChartHashrates) : ?>checked=""<?php endif; ?> /> Hashrates chart</td>
												</tr>
												<tr>
													<td><input type="checkbox" name="dashboard_box_scrypt_earnings" value="1" <?php if ($dashboardBoxScryptEarnings) : ?>checked=""<?php endif; ?> /> Scrypt earnings</td>
													<td><input type="checkbox" name="dashboard_box_log" value="1" <?php if ($dashboardBoxLog) : ?>checked=""<?php endif; ?> /> Miner log</td>
												</tr>
												<tr>
													<td><input type="checkbox" name="dashboard_devicetree" value="1" <?php if ($dashboardDevicetree) : ?>checked=""<?php endif; ?> /> Device tree</td>
													<td></td>
												</tr>
											</table>
										</div>
			                        </div>
			                    </div>
                            
							</section><!-- End right section -->

						</div><!-- End row -->
						                            	                          
						<!-- Pools box -->
                        <div class="box box-primary" id="pools-box">
							<div class="box-header">
								<!-- tools box -->
                                <div class="pull-right box-tools">
                                    <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                                </div><!-- /. tools -->
                                <i class="fa fa-cloud"></i>
                                
                                <h3 class="box-title">Pools Settings</h3>
                            </div>

							<div class="box-body">
								<p>Pools are taken in the order you put them, the first one is the main pool, all the others ones are failovers.</p>
								<!-- Global pool proxy -->
								<div class="form-group">
									<div class="row">
										<div class="col-xs-6">
											<label>Global pool proxy</label>
											<p>Set socks proxy (host:port) for all pools without a proxy specified.</p>
											<div class="input-group">
								    			<span class="input-group-addon"><i class="fa fa-certificate"></i></span>
								    			<input type="text" class="form-control" name="pool_global_proxy" placeholder="socks5|http://proxy:port" value="<?php echo (isset($globalPoolProxy)) ? $globalPoolProxy : ''; ?>" />
								    		</div>
										</div>
									</div>
								</div>

								<div class="form-group">
                                    <div class="row">
										<div class="col-xs-4">
											<strong>Pool URL</strong>
										</div>
										<div class="col-xs-2">
											<strong>Pool Username</strong>
										</div>
										<div class="col-xs-2">
											<strong>Pool Password</strong>
										</div>
										<div class="col-xs-3">
											<strong>Pool Proxy</strong>
										</div>
                                    </div>
								</div>
								<!-- Main Pool -->
								<div class="poolSortable ui-sortable">
									<?php $savedPools = json_decode($minerdPools); $donationPool = false; ?>
									<?php $s = (count($savedPools) == 0) ? 3 : count($savedPools); ?>
									<?php $donationHelp = '<h6><strong>Why can\'t I remove this pool?</strong></h6>
														<p><small>Now, you can remove the donation pool clicking the button below, but if you hadn\'t issue with it and you like Minera, you should think to keep it as failover pool because your support is really needed to continue developing Minera. So please, before clicking the button below, consider keeping the donation pool as at least your latest failover. Thanks for your support. (If you have enabled time donation, this pool is automatically added.)</small></p>
														<p><button class="btn btn-danger btn-xs del-pool-row" name="del-row" value="1"><i class="fa fa-times"></i> Remove donation pool </button></p>'; ?>
									<?php for ($i=0;$i<=$s;$i++) : ?>
										<?php if ( isset($savedPools[$i]->url) && 
													($savedPools[$i]->url == $this->config->item('minera_pool_url') || $savedPools[$i]->url == $this->config->item('minera_pool_url_sha256')) && 
													isset($savedPools[$i]->username) && 
													$savedPools[$i]->username == $this->util_model->getMineraPoolUser() && 
													isset($savedPools[$i]->password) && 
													$savedPools[$i]->password == $this->config->item('minera_pool_password') ) : $donationPool = true; ?>
										<!-- row pool for Minera -->
										<div class="form-group">
										    <div class="row sort-attach">
										    	<div class="col-xs-4">
										    		<div class="input-group">
										    			<span class="input-group-addon"><i class="fa fa-gift"></i></span>
										    			<input type="text" class="form-control" name="pool_url[]" data-ismain="0" value="<?php echo $savedPools[$i]->url ?>" readonly />
										    		</div>
										    	</div>
										    	<div class="col-xs-2">
										    		<div class="input-group">
										    			<span class="input-group-addon"><i class="fa fa-user"></i></span>
										    			<input type="text" class="form-control" name="pool_username[]" data-ismain="0" value="<?php echo $this->util_model->getMineraPoolUser() ?>" readonly />
										    		</div>
										    	</div>
										    	<div class="col-xs-2">
										    		<div class="input-group">
										    			<span class="input-group-addon"><i class="fa fa-lock"></i></span>
										    			<input type="text" class="form-control" name="pool_password[]" data-ismain="0" value="x" readonly />
										    		</div>
										    	</div>
										    	<div class="col-xs-3">
										    		<div class="input-group">
										    			<span class="input-group-addon"><i class="fa fa-certificate"></i></span>
										    			<input type="text" class="form-control" name="pool_proxy[]" data-ismain="0" value="" readonly />
										    		</div>
										    	</div>
										    	<div class="col-xs-1">
										    		<button style="margin-top:5px;" class="btn btn-primary btn-xs help-pool-row" name="help-row" value="1"><i class="fa fa-question"></i></button>
										    	</div>
										    </div>
										    <div class="row minera-pool-help" style="display:none;">
										    	<div class="col-xs-11" style="margin-top:10px">
											    	<div class="callout callout-info">
														<?php echo $donationHelp ?>
													</div>
										    	</div>
										    	<div class="col-xs-1">&nbsp;</div>
										    </div>
										</div>
										<?php else : ?>
										<div class="form-group pool-group">
										    <div class="row sort-attach pool-row">
										    	<div class="col-xs-4">
										    		<div class="input-group">
										    			<span class="input-group-addon"><i class="fa fa-cloud-<?php echo ($i == 0) ? "upload" : "download"; ?>"></i></span>
										    			<input type="text" class="form-control pool_url" placeholder="<?php echo ($i == 0) ? "Main" : "Failover"; ?> url" name="pool_url[]" data-ismain="<?php echo ($i == 0) ? "1" : "0"; ?>" value="<?php echo (isset($savedPools[$i]->url)) ? $savedPools[$i]->url : ''; ?>" />
										    		</div>
										    	</div>
										    	<div class="col-xs-2">
										    		<div class="input-group">
										    			<span class="input-group-addon"><i class="fa fa-user"></i></span>
										    			<input type="text" class="form-control pool_username" placeholder="username" name="pool_username[]" data-ismain="<?php echo ($i == 0) ? "1" : "0"; ?>" value="<?php echo (isset($savedPools[$i]->username)) ? $savedPools[$i]->username : ''; ?>"  />
										    		</div>
										    	</div>
										    	<div class="col-xs-2">
										    		<div class="input-group">
										    			<span class="input-group-addon"><i class="fa fa-lock"></i></span>
										    			<input type="text" class="form-control pool_password" placeholder="password" name="pool_password[]" data-ismain="<?php echo ($i == 0) ? "1" : "0"; ?>" value="<?php echo (isset($savedPools[$i]->password)) ? $savedPools[$i]->password : ''; ?>"  />
										    		</div>
										    	</div>
										    	<div class="col-xs-3">
										    		<div class="input-group">
										    			<span class="input-group-addon"><i class="fa fa-certificate"></i></span>
														<input type="text" class="form-control pool_proxy" placeholder="socks5|http://proxy:port" name="pool_proxy[]" data-ismain="<?php echo ($i == 0) ? "1" : "0"; ?>" value="<?php echo (isset($savedPools[$i]->proxy)) ? $savedPools[$i]->proxy : ''; ?>"  />
										    		</div>
										    	</div>
										    	<div class="col-xs-1">
										    		<button style="margin-top:5px;" class="btn btn-danger btn-xs del-pool-row" name="del-row" value="1"><i class="fa fa-times"></i></button>
										    	</div>
										    </div>
										</div>
										<?php endif; ?>
									<?php endfor; ?>
									<!-- fake donation row pool for Minera -->
									<div class="form-group pool-donation-group" style="display:none;">
									    <div class="row sort-attach">
										    <?php if ($algo === "Scrypt") : ?>
										    	<div class="col-xs-4">
										    		<div class="input-group">
										    			<span class="input-group-addon"><i class="fa fa-gift"></i></span>
										    			<input type="text" class="form-control form-donation" name="pool_url[]" data-ismain="0" value="<?php echo $this->config->item('minera_pool_url') ?>" readonly />
										    		</div>
										    	</div>
										    <?php else: ?>
										    	<div class="col-xs-4">
										    		<div class="input-group">
										    			<span class="input-group-addon"><i class="fa fa-gift"></i></span>
										    			<input type="text" class="form-control form-donation" name="pool_url[]" data-ismain="0" value="<?php echo $this->config->item('minera_pool_url_sha256') ?>" readonly />
										    		</div>
										    	</div>
										    <?php endif; ?>
									    	<div class="col-xs-2">
									    		<div class="input-group">
									    			<span class="input-group-addon"><i class="fa fa-user"></i></span>
									    			<input type="text" class="form-control form-donation" name="pool_username[]" data-ismain="0" value="<?php echo $this->util_model->getMineraPoolUser() ?>" readonly />
									    		</div>
									    	</div>
									    	<div class="col-xs-2">
									    		<div class="input-group">
									    			<span class="input-group-addon"><i class="fa fa-lock"></i></span>
									    			<input type="text" class="form-control form-donation" name="pool_password[]" data-ismain="0" value="x" readonly />
									    		</div>
									    	</div>
									    	<div class="col-xs-3">
									    		<div class="input-group">
									    			<span class="input-group-addon"><i class="fa fa-certificate"></i></span>
													<input type="text" class="form-control pool_proxy" placeholder="socks5|http://proxy:port" name="pool_proxy[]" readonly />
									    		</div>
									    	</div>
									    	<div class="col-xs-1">
									    		<button style="margin-top:5px;" class="btn btn-primary btn-xs help-pool-row" name="help-row" value="1"><i class="fa fa-question"></i></button>
									    	</div>
									    </div>
									    <div class="row minera-pool-help" style="display:none;">
									    	<div class="col-xs-11" style="margin-top:10px">
										    	<div class="callout callout-info">
													<?php echo $donationHelp ?>
												</div>
									    	</div>
									    	<div class="col-xs-1">&nbsp;</div>
									    </div>
									</div>
									<!-- fake row to be cloned -->
									<div class="form-group pool-group pool-group-master" style="display:none;">
									    <div class="row sort-attach pool-row">
									    	<div class="col-xs-4">
									    		<div class="input-group">
									    			<span class="input-group-addon"><i class="fa fa-cloud-download"></i></span>
									    			<input type="text" class="form-control pool_url" placeholder="Failover url" name="pool_url[]" data-ismain="0" value="" />
									    		</div>
									    	</div>
									    	<div class="col-xs-2">
									    		<div class="input-group">
									    			<span class="input-group-addon"><i class="fa fa-user"></i></span>
									    			<input type="text" class="form-control pool_username" placeholder="username" name="pool_username[]" data-ismain="0" value=""  />
									    		</div>
									    	</div>
									    	<div class="col-xs-2">
									    		<div class="input-group">
									    			<span class="input-group-addon"><i class="fa fa-lock"></i></span>
									    			<input type="text" class="form-control pool_password" placeholder="password" name="pool_password[]" data-ismain="0" value=""  />
									    		</div>
									    	</div>
									    	<div class="col-xs-3">
									    		<div class="input-group">
									    			<span class="input-group-addon"><i class="fa fa-certificate"></i></span>
													<input type="text" class="form-control pool_proxy" placeholder="socks5|http://proxy:port" name="pool_proxy[]" data-ismain="0" value=""  />
									    		</div>
									    	</div>
									    	<div class="col-xs-1">
									    		<button style="margin-top:5px;" class="btn btn-danger btn-xs del-pool-row" name="del-row" value="1"><i class="fa fa-times"></i></button>
									    	</div>
									    </div>
									</div>
									
								</div><!-- sortable -->
								<div>
									<button class="btn btn-default btn-sm add-pool-row" name="add-row" value="1"><i class="fa fa-plus"></i> Add row</button><?php if (!$donationPool) : ?>&nbsp;<button class="btn btn-success btn-sm add-donation-pool-row" name="add-row" value="1"><i class="fa fa-gift"></i> Add donation pool</button><?php endif; ?>
								</div>
                            </div>
                        </div>
                        
                        <!-- Custom miners box -->
						<div class="box box-primary" id="customer-miners-box">
						    <div class="box-header">
						    	<!-- tools box -->
	                            <div class="pull-right box-tools">
	                                <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
	                            </div><!-- /. tools -->
	                            <i class="fa fa-desktop"></i>
	                            
	                            <h3 class="box-title">Custom miners</h3>
	                        </div>
						    
	                        <div class="box-body">
						    	<p>Here you can add your own custom miners to be used with Minera, before you start please read this <a href="#" class="open-readme-custom-miners">FAQ</a>.</p>
						    	
						    	<div class="callout callout-info">
							    	<?php if (count($customMiners) > 0) : ?>
										<p class="margin-bottom">I found the following custom miners available, to add or remove just select on or off and save. You'll find them in the preferred miners select below.</p>
							    		<?php foreach ($customMiners as $customMiner) : $disabled = false; $customLabel = "primary"; ?>
							    			<?php if (strtolower($customMiner) == "bfgminer" || strtolower($customMiner) == "cpuminer" || strtolower($customMiner) == "cgminer" || strtolower($customMiner) == "cgminer-dmaxl-zeus") : $disabled = true; $disabledMsg = "You can't use an existent name, change your filename"; $customLabel = "default"; endif; ?>
											<?php if (preg_match("/.*[\.|-]$/", $customMiner) || preg_match("/\\s/", $customMiner) || preg_match("/\//", $customMiner)) : $disabled = true; $disabledMsg = "Filename is not good, avoid symbols at the end of the name, spaces or slashes everywhere"; $customLabel = "default"; endif; ?>
								    		<div class="input-group margin-bottom">
									    		<span class="btn btn-danger btn-xs del-custom-miner" data-custom-miner="<?php echo $customMiner ?>" data-toggle="tooltip" data-title="Completely remove the custom miner, this also DELETE the file!" data-trigger="hover"><i class="fa fa-times"></i></span>&nbsp;
												<label>
													<input type="checkbox" 
														name="active_custom_miners[]" 
														value="<?php echo $customMiner ?>" 
														<?php if ($activeCustomMiners && in_array($customMiner, $activeCustomMiners)) : ?>checked<?php endif; ?> 
														<?php if ($disabled) : ?>disabled<?php endif; ?> 
													/>
													&nbsp;<span class="label label-<?php echo $customLabel ?>"><?php echo $customMiner ?></span><?php if ($disabled) : ?> <small style="font-weight: normal">(<?php echo $disabledMsg ?>)</small><?php endif; ?> 
												</label>
								    		</div>
							    		<?php endforeach; ?>
							    	<?php else : ?>
							    		<p><h6>It seems you haven't any custom miner. If you wanna add it, you need to build your binary and put it in the custom miner folder:</h6> <code><?php echo FCPATH.'minera-bin/custom/'; ?></code></p>
							    	<?php endif; ?>
						    	</div>

						    	<h6>* Don't call your custom binary file as "bfgminer", "cgminer" or any other existent (built-in) miner. Minera won't permit you to use it. If this happens change the filename.</h6>

								<h6>** After you turn on your custom miner and save, you need to select it from your preferred miner below and remember to setup it.</h6>
								
						    	<div class="callout callout-grey readme-custom-miners" style="display:none;">
					    		    <h6><strong>How can I upload my own custom miner?</strong></h6>
					    		    <p><small>You can't upload the binary file by web interface, it's not logic building the miner on another system and upload it to Minera, it's better building it in Minera itself.</small></p>
					    		    
					    		    <h6><strong>So, what are the steps to do it?</strong></h6>
					    		    <p><small><strong>SSH</strong> into Minera, <strong>build</strong> your custom miner in any directory you want, "<strong>sudo make install</strong>" it (so libraries get installed correctly), <strong>copy</strong> ONLY the binary file into <code><?php echo FCPATH ?>minera-bin/custom</code> directory, <strong>refresh</strong> this page you'll find your custom miner here.</small></p>
					    		    
					    		    <h6><strong>Can I use any miner binary?</strong></h6>

					    		    <p><strong>NO!</strong> <small>Miners must be forks of CGminer or BFGminer, there are small probability you can add different miners than those, the main problem is how the miner send stats and it must be compatibile to Minera.</small></p>

					    		    <h6><strong>My miner should be compatible but it isn't working</strong></h6>
					    		    <p><small>Check your binary works on your Minera system, SSH into it and try to launch it manually, probably it lacks on missing external libraries, try to recompile it on Minera and do "sudo make install" at the end.</small></p>
					    		    
					    		    <h6><strong>Can I use this feature if I'm completely newbie to mining and Linux?</strong></h6>
					    		    <p><small>Well, short answer should be "No", the long one is: you could try, but it needs a lot of skills to do this and if you are a newbie it's recommended you start with a pre-compiled miner software, Minera has 4 built-in, start with them, then try to <a href="https://bitcointalk.org/index.php?topic=596620.0">ask to the forum</a> before playing with this feature.</small></p>
					    		    
					    		    <h6><strong>Can I completely brake my Minera using this feature?</strong></h6>
					    		    <p><small>No, you can always rollback to a built-in miner, just select it from your preferred miners below (you can also save each config and re-load them when you need, check the Import/export section below).</small></p>
					    		    
					    		    <h6><strong>Is this feature stable enough to be used without any issue?</strong></h6>
					    		    <p><strong>NO!</strong> <small>This is intended as "beta" feature, if you wanna live happy with your Minera, save/export always your settings before doing something like enabling this.</small></p>
					    		</div>

	                        </div>
	                    </div>
	                    
						<!-- Miner box -->
                        <div class="box box-primary" id="local-miner-box">
							<div class="box-header">
								<!-- tools box -->
                                <div class="pull-right box-tools">
                                    <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                                </div><!-- /. tools -->
                                <i class="fa fa-gear"></i>
                                
                                <h3 class="box-title">Local Miner Settings</h3>
                            </div>

                                <div class="box-body">
									
									<div class="callout callout-grey">
										<h4>Select your preferred miner software</h4>
										<div class="form-group group-minerdsoftware">
                                            <label>Currently selected: <span class="badge bg-green"><?php echo $minerdSoftware ?></span></label>
											<select name="minerd_software" id="minerd-software" class="form-control">
												<option value="cpuminer" <?php if ($minerdSoftware == "cpuminer") : ?>selected<?php endif; ?>>CPUminer (GC3355 fork)</option>
												<option value="bfgminer" <?php if ($minerdSoftware == "bfgminer") : ?>selected<?php endif; ?>>BFGminer 5.x (Official)</option>
												<option value="cgminer" <?php if ($minerdSoftware == "cgminer") : ?>selected<?php endif; ?>>CGminer 4.x (Official)</option>
												<option value="cgdmaxlzeus" <?php if ($minerdSoftware == "cgdmaxlzeus") : ?>selected<?php endif; ?>>CGminer (Dmaxl Zeus fork)</option>
												<?php if ($activeCustomMiners) : ?>
													<?php foreach ($activeCustomMiners as $activeCustomMiner) : ?>
														<option value="<?php echo $activeCustomMiner ?>" <?php if ($minerdSoftware == $activeCustomMiner) : ?>selected<?php endif; ?>>[Custom Miner] <?php echo $activeCustomMiner ?></option>
													<?php endforeach; ?>
												<?php endif; ?>
											</select>
											<h6>Pay attention: Minera is not responsible of any problem related to the miner software you are using. Minera acts only as frontend to manage the miner software. Please refer to miner software's related authors if you have question about them and how to use them.</h6>
											<div>
												<select name="miners_conf" id="miners-conf" class="form-control">
													<option value="">--- Preconfigured miner options ---</option>
													<?php foreach ($builtInMinersConf as $minerPreConf) : ?>
														<option value="<?php echo $minerPreConf->miner_options ?>" data-miner-id="<?php echo $minerPreConf->miner ?>"><?php echo $minerPreConf->miner_name." - ".$minerPreConf->miner_device ?></option>
													<?php endforeach; ?>
												</select>
												<h6>If you use preconfigured options check them before starting the miner, some of them require manual input in the manual options textarea below</h6>
												<p class="miners-conf-box mt10 mb10"></p>
											</div>
										</div>
									</div>

									<div class="options-selection">
										<p>Select the options to launch the miner command.</p>

										<div class="row row-btn-options">
											<div class="col-xs-2">
												<a href="#"><button class="btn btn-default btn-sm <?php if ($minerdGuidedOptions) : ?>disabled<?php endif; ?> btn-guided-options">Guided</button></a>&nbsp;
												<a href="#"><button class="btn btn-default btn-sm <?php if ($minerdManualOptions) : ?>disabled<?php endif; ?> btn-manual-options">Manual</button></a>
											</div>
											<div class="col-xs-10"></div>
										</div>
										<input type="hidden" id="guided_options" name="guided_options" value="<?php echo $minerdGuidedOptions ?>" />
										<input type="hidden" id="manual_options" name="manual_options" value="<?php echo $minerdManualOptions ?>" />
									</div>
									<hr />
									
									<!-- General options -->
									<div class="general-options">
										<!-- Logging -->
										<div class="form-group" id="minerd-log">
											<div class="checkbox">
												<label>
													<input type="checkbox" name="minerd_log" value="1" <?php if ($minerdLog) : ?>checked=""<?php endif; ?> />
													Enable logging <small class="legend-option-log"></small>
												</label>                                                
											</div>
										</div>
										
										<!-- Append miner conf -->
										<div class="form-group">
											<div class="checkbox">
												<label>
													<input type="checkbox" name="minerd_append_conf" value="1" <?php if ($minerdAppendConf) : ?>checked=""<?php endif; ?> />
													Append JSON conf <small>(-c /var/www/minera/conf/miner_conf.json)</small>
												</label>                                                
											</div>
										</div>
									</div>
									
									<div class="guided-options">

										<!-- Scrypt -->
										<div class="form-group" id="minerd-scrypt">
											<div class="checkbox">
												<label>
													<input type="checkbox" name="minerd_scrypt" value="1" <?php if ($minerdScrypt) : ?>checked=""<?php endif; ?> />
													Enable scrypt algo <small>you should select this if you are mining alternate crypto currencies (--scrypt)</small>
												</label>                                                
											</div>
										</div>
										
										<!-- Auto-Detect -->
										<div class="form-group">
											<div class="checkbox">
												<label>
													<input type="checkbox" name="minerd_autodetect" value="1" <?php if ($minerdAutodetect) : ?>checked=""<?php endif; ?> />
													Enable device auto detection <small class="legend-option-autodetect"></small>
												</label>                                                
											</div>
										</div>
										
										<!-- Debug -->
										<div class="form-group">
											<div class="checkbox">
												<label>
													<input type="checkbox" name="minerd_debug" value="1" <?php if ($minerdDebug) : ?>checked=""<?php endif; ?> />
													Enable debug <small>(--debug)</small>
												</label>                                                
											</div>
										</div>									

										<!-- Auto-Tune -->
										<div class="form-group" id="minerd-autotune">
											<div class="checkbox">
												<label>
													<input type="checkbox" name="minerd_autotune" value="1" <?php if ($minerdAutotune) : ?>checked=""<?php endif; ?> />
													Enable frequency auto tuning <small>(--gc3355-autotune)</small>
												</label>                                                
											</div>
										</div>
										
										<!-- Start Frequency -->												
										<div class="form-group" id="minerd-startfreq">
											<label>Select starting frequency</label>
											<div class="margin-bottom" style="width:50%">
												<input type="text" name="minerd_startfreq" id="ion-startfreq" value="" data-saved-startfreq="<?php echo (isset($minerdStartfreq)) ? $minerdStartfreq : 800; ?>"/>
											</div>
											<h6>You can select a default frequency value to start with.</h6>
										</div>

                                        <!-- Minerd extra options -->
                                        <div class="form-group">
                                            <label>Extra options</label>
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-cogs"></i></span>
												<input type="text" class="form-control" placeholder="Extra options" name="minerd_extraoptions" value="<?php echo $minerdExtraoptions ?>" />
											</div>
                                            <h6>Write here any other option you want to include. (suggested: --retries=1)</h6>
                                        </div>	                                        
                                        
									</div>
                                    
                                    <!-- Minerd manual options config -->
                                    <div class="form-group manual-options">
                                        <label>Manual options</label>
                                        <p>You have chosen to add all options manually, I will only add for you the pools list, you have to take care of the rest.</p>
                                        <textarea name="minerd_manual_settings" class="form-control manual-settings" rows="5" placeholder="Example: --gc3355-detect --gc3355-autotune --freq=850 --retries=1" class="minerd_manual_settings"><?php echo $minerdManualSettings ?></textarea>
										<h6>Please do not include the command name or the pools (they are automatically added).</h6>
									</div>
									
									<!-- Minerd API Allow -->
                                    <div class="form-group" id="minerd-api-allow">
                                        <label>API Allow</label>
										<div class="row">
											<div class="col-xs-12 col-md-4">
												<div class="input-group margin-bottom">
													<span class="input-group-addon"><i class="fa fa-crosshairs"></i></span>
													<input type="text" class="form-control" placeholder="" readonly="readonly" name="minerd_api_allow" value="W:127.0.0.1" />
												</div>
												<div class="input-group">
													<span class="input-group-addon"><i class="fa fa-crosshairs"></i></span>
													<input type="text" class="form-control" placeholder="[W:]IP[/Prefix] address[/subnets]" name="minerd_api_allow_extra" value="<?php echo $minerApiAllowExtra ?>" />
												</div>
											</div>
										</div>
										<h6>If you need to allow API to listen to more ip/networks you can add them here. (First one must stay for Minera)</h6>
                                    </div>
                                    											
									<!-- Minerd delay time option -->
                                    <div class="form-group">
                                        <label>Autostart Delay Time</label>
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
											<input type="text" class="form-control" placeholder="Delay time" name="minerd_delaytime" value="<?php echo $minerdDelaytime ?>" style="width:90px"/>
										</div>
										<h6>Seconds to wait before starting minerd (on boot).</h6>
                                    </div>
                                    
                                    <!-- Minerd autorestart -->
                                    <div class="form-group">
                                        <label>Autorestart if devices are possible dead</label>
                                        <div class="checkbox">
											<label>
												<input type="checkbox" class="minerd-autorestart" name="minerd_autorestart" value="1" <?php if ($minerdAutorestart) : ?>checked=""<?php endif; ?> />
												Enable miner auto-restart <small>(if there are more or equal devices dead it will restart the miner software.)</small>
											</label>                                                
										</div>
										<div class="row">
											<div class="col-xs-3 col-md-2">
												<small>Number of Dead devices</small>
												<div class="input-group">
													<span class="input-group-addon"><i class="glyphicon glyphicon-hdd"></i></span>
													<input type="text" class="form-control" placeholder="Devices" name="minerd_autorestart_devices" value="<?php echo $minerdAutorestartDevices ?>" style="width:90px"/>
												</div>
											</div>
											<div class="col-xs-3 col-md-2">
												<small>Seconds to wait</small>
												<div class="input-group">
													<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
													<input type="text" class="form-control" placeholder="Seconds" name="minerd_autorestart_time" value="<?php echo ($minerdAutorestartTime>0) ? $minerdAutorestartTime : 600; ?>" style="width:90px"/>
												</div>
											</div>
										</div>
										<h6>Check based on last share time (seconds selected without any share sent triggers the restart)</h6>
                                    </div>
									
									<!-- Auto-recover -->
									<div class="form-group">
										<label>Miner Autorecover</label>
										<div class="checkbox">
											<label>
												<input type="checkbox" name="minerd_autorecover" value="1" <?php if ($minerdAutorecover) : ?>checked=""<?php endif; ?> />
												Enable auto-recover mode <small>(If minerd process dies Minera restarts it)</small>
											</label>                                                
										</div>
									</div>
									
									<!-- Use root -->
									<div class="form-group">
										<label>Miner superuser (root)</label>
										<div class="checkbox">
											<label>
												<input type="checkbox" name="minerd_use_root" value="1" <?php if ($minerdUseRoot) : ?>checked=""<?php endif; ?> />
												Enable superuser mode <small>(<strong>If your devices cannot get recognised please try enabling this option.</strong> The miner process will start with superuser/root rights, useful for some kind of devices.</small>)
											</label>                                                
										</div>
									</div>
									
									<hr />
									
                                    <!-- Minerd final config -->
                                    <h3><i class="fa fa-check"></i> Check your miner settings</h3>
									<div class="callout callout-info">
										<h4>Miner will start with this syntax:</h4>
										<h5><i><?php echo $this->config->item("screen_command") ?> <?php echo $this->config->item("minerd_command")."</i> <strong>".$minerdSettings ?></strong></h5>
										<?php if ($minerdAppendConf) : ?>
											<h4>JSON Conf:</h4>
											<pre style="font-size:10px;"><?php $jsonConf =  json_decode($minerdJsonSettings); echo json_encode($jsonConf, JSON_PRETTY_PRINT); ?></pre>
										<?php else: ?>
											<h4 class="text-red">Warning you selected to not append the JSON conf.</h4>
										<?php endif; ?>
									</div>

									<?php if ($minerdSoftware == "cpuminer" && $savedFrequencies) : ?>
										<h3>Saved frequencies</h3>
										<div class="callout callout-light">
											<h6>Here is the string you can add to the extra options, but remember to uncheck the autotune option:</h6>
											<pre  id="miner-freq" style="font-size:10px">--gc3355-freq=<?php echo $savedFrequencies ?></pre>
										</div>
									<?php endif; ?>
										
                            </div>
                        </div>
	                    
	                    <!-- Network Miners box -->
						<div class="box box-primary" id="network-miners-box">
						    <div class="box-header">
						    	<!-- tools box -->
	                            <div class="pull-right box-tools">
	                                <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
	                            </div><!-- /. tools -->
	                            <i class="fa fa-server"></i>
	                            
	                            <h3 class="box-title">Network Miners Settings</h3>
	                        </div>
						    
	                        <div class="box-body">
								<p>You can scan your network or add your network device manually. If you have miners like Antminer S1/S2 or RockMiner R3-Box or any miner with a networked connection, now you can control them in Minera.</p>
								<h6>Network names are picked up randomly from a small constellation database, you can change it.</h6>
						    	<p><button class="btn bg-olive scan-network">Scan network</button></p>

								<div class="alert alert-warning alert-no-net-devices" style="display:none">There aren't new network devices, try to add them manually.</div>

								<div class="form-group">
                                    <div class="row">
										<div class="col-xs-1">
											<strong>Status</strong>
										</div>
										<div class="col-xs-3">
											<strong>Miner Name</strong>
										</div>
										<div class="col-xs-3">
											<strong>Miner IP</strong>
										</div>
										<div class="col-xs-2">
											<strong>Miner Port</strong>
										</div>
										<div class="col-xs-2">
											<strong>Miner Algorithm</strong>
										</div>
                                    </div>
								</div>
								<!-- Main Pool -->
								<div class="netSortable ui-sortable">
									<?php if (count($networkMiners) > 0) : ?>
										<?php foreach($networkMiners as $networkMiner) : $isOnlineNet = $this->util_model->checkNetworkDevice($networkMiner->ip, $networkMiner->port); ?>
										<div class="form-group net-group">
										    <div class="row sort-attach net-row">
										    	<div class="col-xs-1 text-center">
										    		<span class="label <?php if ($isOnlineNet) : ?>label-success<?php else : ?>label-danger<?php endif; ?> net_miner_status"><?php if ($isOnlineNet) : ?>Online<?php else: ?>Offline<?php endif; ?></span>
										    	</div>
										    	<div class="col-xs-3">
										    		<div class="input-group">
										    			<span class="input-group-addon"><i class="fa fa-server"></i></span>
										    			<input type="text" class="form-control net_miner_name" placeholder="Miner Name" name="net_miner_name[]" value="<?php echo (isset($networkMiner->name)) ? $networkMiner->name : ''; ?>" />
										    		</div>
										    	</div>
										    	<div class="col-xs-3">
										    		<div class="input-group">
										    			<span class="input-group-addon"><i class="fa fa-hdd-o"></i></span>
										    			<input type="text" class="form-control net_miner_ip" placeholder="Miner Ip Address" name="net_miner_ip[]" value="<?php echo (isset($networkMiner->ip)) ? $networkMiner->ip : ''; ?>" />
										    		</div>
										    	</div>
										    	<div class="col-xs-2">
										    		<div class="input-group">
										    			<span class="input-group-addon"><i class="fa fa-arrow-right"></i></span>
										    			<input type="text" class="form-control net_miner_port" placeholder="Miner Port" name="net_miner_port[]" value="<?php echo (isset($networkMiner->port)) ? $networkMiner->port : ''; ?>" />
										    		</div>
										    	</div>
										    	<div class="col-xs-2">
										    		<div class="input-group">
										    			<select class="form-control net_miner_algo" name="net_miner_algo[]">
											    			<option <?php if (isset($networkMiner->algo) && $networkMiner->algo === "SHA-256") echo "selected" ?>>SHA-256</option>
											    			<option <?php if (isset($networkMiner->algo) && $networkMiner->algo === "Scrypt") echo "selected" ?>>Scrypt</option>

										    			</select>
										    		</div>
										    	</div>
										    	<div class="col-xs-1">
										    		<button style="margin-top:5px;" class="btn btn-danger btn-xs del-net-row" name="del-net-row" value="1"><i class="fa fa-times"></i></button>
										    	</div>
										    </div>
										</div>
										<?php endforeach; ?>
									<?php endif; ?>
									<!-- fake row to be cloned -->
									<div class="form-group net-group net-group-master" style="display:none;">
									    <div class="row sort-attach net-row">
									    	<div class="col-xs-1 text-center">
									    		<span style="width: 40px;" class="label label-primary net_miner_status">New</span>
									    	</div>
									    	<div class="col-xs-3">
									    		<div class="input-group">
									    			<span class="input-group-addon"><i class="fa fa-server"></i></span>
									    			<input type="text" class="form-control net_miner_name" placeholder="Miner Name" name="net_miner_name[]" value="" />
									    		</div>
									    	</div>
									    	<div class="col-xs-3">
									    		<div class="input-group">
									    			<span class="input-group-addon"><i class="fa fa-hdd-o"></i></span>
									    			<input type="text" class="form-control net_miner_ip" placeholder="Miner Ip Address" name="net_miner_ip[]" value="" />
									    		</div>
									    	</div>
									    	<div class="col-xs-2">
									    		<div class="input-group">
									    			<span class="input-group-addon"><i class="fa fa-arrow-right"></i></span>
									    			<input type="text" class="form-control net_miner_port" placeholder="Miner Port" name="net_miner_port[]" value="" />
									    		</div>
									    	</div>
									    	<div class="col-xs-2">
									    		<div class="input-group">
									    			<select class="form-control net_miner_algo" name="net_miner_algo[]">
										    			<option>SHA-256</option>
										    			<option>Scrypt</option>
									    			</select>
									    		</div>
									    	</div>
									    	<div class="col-xs-1">
									    		<button style="margin-top:5px;" class="btn btn-danger btn-xs del-net-row" name="del-net-row" value="1"><i class="fa fa-times"></i></button>
									    	</div>
									    </div>
									</div>
									
								</div><!-- sortable -->
								<div>
									<button class="btn btn-default btn-sm add-net-row" name="add-net-row" value="1"><i class="fa fa-plus"></i> Add Network Miner</button>
								</div>								
	                        </div>
						    <div class="box-footer">
						    	<p class="small">Pools for network devices can be handle from the dashboard</p>
						    </div>
	                    </div>
	                                            
                        <!-- System box -->
						<div class="box box-primary" id="system-box">
							<div class="box-header">
								<!-- tools box -->
                                <div class="pull-right box-tools">
                                    <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                                </div><!-- /. tools -->
                                <i class="fa fa-rocket"></i>
                                
                                <h3 class="box-title">System Settings</h3>
                            </div>
							
                            <div class="box-body">
								<p>Setup the system options</p>

									<!-- hostname -->
                                    <div class="form-group">
                                        <label>System hostname</label>
										<p>Current hostname is: <span class="badge bg-blue"><?php echo $mineraHostname ?></span></p>
                                        <p>You can change the Raspbian hostname where your Minera is running</p>
                                        <div class="input-group">
	                                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
	                                        <input type="text" name="system_hostname" class="form-control" placeholder="Use numbers/letters, symbols allowed are dash and underscore" />
                                        </div>
									</div>
									
									<!-- system password -->
                                    <div class="form-group">
                                        <label>System password</label>
                                        <p>Minera works with the system user <span class="badge bg-blue">minera</span>, here you can change the system user password</p>
                                        <div class="input-group">
											<span class="input-group-addon"><i class="fa fa-user-secret"></i></span>
											<input type="password" class="form-control" id="system_password" name="system_password" placeholder="Password for Minera system user">
										</div>
										<div class="input-group mt10">
											<span class="input-group-addon"><i class="fa fa-user-secret"></i></span>
											<input type="password" class="form-control" name="system_password2" placeholder="Repeat the password to validate it">
										</div>
										<h6>This is not the web password! This is the system user password you should use to login into the system by SSH. For the <a href="#user-box">web password look below</a>.
									</div>
									
									<!-- timezone -->
                                    <div class="form-group">
                                        <label>System timezone</label>
                                        <p>Current system time is: <span class="badge bg-blue"><?php echo date("c", time()); ?></span></p>
                                        <p>You should change the timezone to reflect yours</p>
										<select name="minera_timezone" class="form-control">
											<?php foreach ($timezones as $timezone) : ?>
												<option<?php echo ($mineraTimezone == $timezone) ? " selected" : ""; ?>><?php echo $timezone ?></option>
											<?php endforeach; ?>
										</select>
									</div>
									
									<!-- rc.local extra commands -->
                                    <div class="form-group">
                                        <label>Startup extra commands (rc.local)</label>
                                        <p>If you need to launch any other extra command on boot, you can place them here. Each line will be appended to the file /etc/rc.local</p>
                                        <textarea name="system_extracommands" class="form-control system_extracommands" rows="5" placeholder="There isn't any error control here"><?php echo $systemExtracommands ?></textarea>
										<h6>(WARNING: you could harm your controller putting wrong strings here.)</h6>
									</div>
									
									<!-- scheduled event -->
									<div class="form-group">
                                        <label>Scheduled event</label>
                                        <p>Here you can schedule to reboot the system or restart the miner every X hours</p>
                                        <p><?php if ($scheduledEventTime > 0) : ?><span class="badge bg-green"><?php echo strtoupper($scheduledEventAction) ?> every <?php echo $scheduledEventTime ?> hour(s)</span>  Next event at about: <small class="label label-light"><?php echo date("c", (($scheduledEventTime*3600) + $scheduledEventStartTime))?></small><?php else : ?><span class="badge bg-muted">Disabled</span><?php endif; ?></p>
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
											<input type="text" class="form-control scheduled-event-time" placeholder="Hour(s)" name="scheduled_event_time" value="<?php echo $scheduledEventTime ?>" style="width:90px">&nbsp;
											<label>
												<input type="radio" class="event-reboot-radio" name="scheduled_event_action" value="reboot" <?php if ($scheduledEventAction == "reboot") : ?>checked=""<?php endif; ?> />
												Reboot System
											</label>&nbsp;
											<label>
												<input type="radio" class="event-restart-radio" name="scheduled_event_action" value="restart" <?php if ($scheduledEventAction == "restart") : ?>checked=""<?php endif; ?> />
												Restart Miner
											</label>
										</div>
										<h6>If you leave the hours empty it will be disabled.</h6>
									</div>
									
									<!-- anonymus stats -->
                                    <div class="form-group">
                                        <label>Send anonymous stats</label>
                                        <p>Join the Minera community! Send your completely anonymous stats to help growing the total Minera hashrate.</p>
										<div class="checkbox">
											<label>
												<input type="checkbox" class="anonymous-checkbox" name="anonymous_stats" value="1" <?php if ($anonymousStats) : ?>checked=""<?php endif; ?> />
												Enable Anonymous Stats
											</label>                                                
										</div>
										<h6>(Stats included are: total hashrate, devices count and miner used. No IP, host or any other data will be sent. Stats are collected and sent every hour. With the stats you will be able to see some cool numbers on the <a href="http://getminera.com">Minera website</a>)</h6>
									</div>
												
                            </div>
                        </div>
                        
						<!-- Import/Export box -->
						<div class="box box-primary" id="importexport-box">
						    <div class="box-header">
						    	<!-- tools box -->
	                            <div class="pull-right box-tools">
	                                <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
	                            </div><!-- /. tools -->
	                            <i class="fa fa-code-fork"></i>
	                            
	                            <h3 class="box-title">Import/Export/Share Settings</h3>
	                        </div>
						    
	                        <div class="box-body">
						    	<p>You can export a JSON file with all the settings from your current Minera system. This file can be imported to reproduce the same settings in a new Minera system with a click (this will export everything excluding: user password, charts and stats). You can also save a single miner config to be used in future<em>*</em> or shared with the Minera community<em>**</em>.</p>
						    	
								<div class="import-export-box margin-bottom">
									<span class="btn btn-success fileinput-button" data-toggle="tooltip" data-title="File must be a JSON export file from a Minera system">
										<i class="glyphicon glyphicon-plus"></i>
										Import file...
										<input class="import-file" type="file" name="import_system_config">
									</span>
									<span class="btn btn-warning export-action" data-toggle="tooltip" data-title="This generates a JSON file to be imported into Minera">
										<i class="glyphicon glyphicon-download-alt"></i>
										Export Settings
									</span> 
									<span class="btn btn-default save-config-action" data-toggle="tooltip" data-title="This saves only the miner config to be used or shared later">
										<i class="glyphicon glyphicon-floppy-disk"></i>
										Save Miner Config
									</span>
								</div>
						    	
								<!-- The global progress bar -->
								<div id="progress" class="progress">
									<div class="progress-bar progress-bar-success"></div>
								</div>
								<!-- The container for the uploaded files -->
								<div id="files" class="files"></div>
						    	
									<div class="saved-configs" <?php if (!$savedConfigs) : ?>style="display:none;"<?php endif; ?>>
									    <div class="table-responsive">
									    	<table id="saved-configs-table" class="table table-striped datatable">
									    		<thead>
									    			<tr>
									    				<th>Date</th>
									    				<th>Software</th>
									    				<th style="width:35%">Settings</th>
									    				<th>Pools</th>
									    				<th style="width:8%">Actions</th>
									    			</tr>
									    		</thead>
									    		<tbody>
												<?php if ($savedConfigs) : ?>
									    		<?php foreach ($savedConfigs as $savedConfig) : $savedConfig = json_decode(base64_decode($savedConfig));?>
									    			<tr class="config-<?php echo $savedConfig->timestamp ?>">
									    			<td>
									    				<small class="label label-info"><?php echo date("m/d/y h:i a", $savedConfig->timestamp) ?></small>
									    			</td>
									    			<td>
									    				<small class="label bg-blue"><?php echo $savedConfig->software ?></small>
									    			</td>
									    			<td>
									    				<small class="font-bold"><?php echo $savedConfig->settings ?></small>
									    			</td>
									    			<td>
										    			<small>
										    			<?php foreach ($savedConfig->pools as $savedPool) : ?>
										    				<?php echo $savedPool->url ?> <i class="fa fa-angle-double-right"></i> <?php echo $savedPool->username ?><br />
										    			<?php endforeach; ?>
										    			</small>
									    			</td>
									    			<td class="text-center">
									    				<a href="#" class="share-config-open" data-config-id="<?php echo $savedConfig->timestamp ?>" data-toggle="tooltip" data-title="Share saved config"><i class="fa fa-share-square-o"></i></a>
									    				<a href="#" class="load-config-action" style="margin-left:10px;" data-config-id="<?php echo $savedConfig->timestamp ?>" data-toggle="tooltip" data-title="Load saved config"><i class="fa fa-upload"></i></a>
									    				<a href="#" class="delete-config-action" style="margin-left:10px;" data-config-id="<?php echo $savedConfig->timestamp ?>" data-toggle="tooltip" data-title="Delete saved config"><i class="fa fa-times"></i></a>
									    			</td>
									    			</tr>
									    		<?php endforeach; ?>
												<?php endif; ?>
									    		</tbody>
									    		<tfoot>
									    		</tfoot>
									    	</table>
									    </div>
									</div>
								
	                        </div>
							<div class="box-footer">
								<h6><em>*</em> Loading a saved miner config sets the manual settings mode with the saved command line, sets the miner software and completely overwrites the pools settings.</h6>
								<h6><em>**</em> Sharing the miner config to the Minera community won't share your pools settings</h6>
							</div>
	                    </div>

					</form>

					<!-- User box -->
					<div class="box box-primary" id="user-box">
						<div class="box-header">
							<!-- tools box -->
                            <div class="pull-right box-tools">
                                <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                            </div><!-- /. tools -->
                            <i class="fa fa-user"></i>
                            
                            <h3 class="box-title">User</h3>
                        </div>
						
						<form action="<?php echo site_url("app/settings") ?>" method="post" role="form" id="minerapassword">
							<input type="hidden" name="save_password" value="1" />
                            <div class="box-body">
								<p>Change the Minera lock screen password</p>
                               	<label for="password1">Password</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-lock"></i></span>
									<input type="password" class="form-control" name="password" placeholder="Lock screen password">
								</div>
								<div class="input-group mt10">
									<span class="input-group-addon"><i class="fa fa-lock"></i></span>
									<input type="password" class="form-control" name="password2" placeholder="Repeat the lock screen password">
								</div>
                            </div>
							<div class="box-footer">
								<button type="submit" class="btn btn-primary save-minera-password">Save password</button>
							</div>
						
						</form>
                    </div>
                    
					<!-- Reset box -->
					<div class="box box-primary" id="resets-box">
						<div class="box-header">
							<!-- tools box -->
                            <div class="pull-right box-tools">
                                <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                            </div><!-- /. tools -->
                            <i class="fa fa-warning"></i>
                            
                            <h3 class="box-title">Resets</h3>
                        </div>
						
                        <div class="box-body">
	                        <div class="row">
	                        <div class="col-md-10">
								<p>If you are in trouble or you wanna start over, you can resets some of the stored data or reset everything to factory default.</p>
								<div class="form-group">
	                            	<button type="submit" class="btn btn-warning reset-action" data-reset-action="charts"><i class="fa fa-eraser"></i> Reset Charts data</button>
	                            	<h6>This resets all the stored stats needed by the charts, so charts will start from zero.</h6>
								</div>
								<div class="form-group">
	                            	<button type="submit" class="btn btn-primary reset-action" data-reset-action="options"><i class="fa fa-eraser"></i> Reset Guided/manual settings</button>
									<h6>If you have problem choosing between guided/manual options above you can reset them here.</h6>
								</div>
								<div class="form-group">
	                            	<button type="submit" class="btn btn-warning reset-action" data-reset-action="logs"><i class="fa fa-eraser"></i> Clear the Minera logs</button>
									<h6>This will delete everything inside application/logs. This includes all Minera application logs and also all the Miner logs.</h6>
								</div>
								<div class="form-group">
	                            	<button type="submit" class="btn btn-danger reset-factory-action"><i class="fa fa-recycle"></i> Reset to factory default</button>
									<h6>This will reset your Minera to the factory default settings (it doesn't change anything at system level, only the web interface with all the relative data will be reset, this includes: lock password, stats, charts, miner settings, saved miner configs, pools, etc...)</h6>
								</div>
	                        </div>
	                        </div>
                        </div>
						<div class="box-footer">
							<h6><strong>Clicking the reset buttons resets data immediately, there isn't any confirmation to do. Reset actions aren't recoverable, data will be lost.</strong></h6>
						</div>
                    </div>
                
                </section><!-- /.left col -->
                
			</div><!-- /.row -->

        </section><!-- /.content -->
    </aside><!-- /.right-side -->
</div><!-- ./wrapper -->
