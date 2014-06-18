            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side ">                
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Mining
                        <small>Settings</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo site_url("app/dashboard") ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">

					<div class="row">
                        
                        <?php if (isset($message)) : ?>
	                        <section class="col-md-12">
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
                        
                        
                        <!-- Top section -->
                        <section class="col-md-12">
								
							<form action="<?php site_url("app/dashboard") ?>" method="post" role="form" id="minersettings">
								<input type="hidden" name="save_settings" value="1" />                                                    

								<div class="row">
									<section class="left-section col-xs-12 col-md-6">
									
										<!-- Donation box -->
										<div class="box box-primary">
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
										    		    <input type="text" name="minera_donation_time" id="option-minera-donation-time" value="" />
										    		</div>
										    		
										    		<p class="donation-worth"></p>
										    		
										    		<p><small class="donation-mood badge">&nbsp;</small></p>
										    		
										    		<div class="callout callout-grey readme-donation" style="display:none;">
										    		    <h6><strong>How does time donation work?</strong></h6>

										    		    <p><small>It's really simple, you can select how many minutes per day you want to donate to Minera, you can choose between 15 minutes and 6 hours. If you leave it at zero minutes, donation will be disabled. If you enable it, every day (at about 4.10am system time) Minera will automatically switch to the donation pool for the amount of time you have selected. When that period is over Minera will switch back to your main pool. Pool changes are on the fly, so it doesn't need to save/restart anything.</small></p>

										    		    <h6><strong>What does happen if Minera reboot/shutdown/stop during the donation period?</strong></h6>
										    		    <p><small>Absolutely nothing. I mean, if your Minera system got problems while is running on the donation pool (for example it reboots as a result of a power failure), it just goes back to the main pool. It won't retry to switch the donation pool until the next day.</small></p>
										    		    <h6><strong>How do you calculate the amount donated?</strong></h6>
										    		    <p><small>It's just an approximate. I simply divide the time in minutes you donate per fixed average profitability of 0.0018 <i class="fa fa-btc"></i>/Day, the function is: <i>0.0018 / 24 / 60 x donation_minutes</i>.</small></p>
										    		    
										    		    <h6><strong>What do you do with the money received?</strong></h6>
										    		    <p><small>Thanks to your kind donations I can buy some of the new hardware coming out and add its support to Minera. So next time there is a new cool hardware, Minera will be ready for it. I also need beer to do all this cool stuff :)</small></p>
										    		    <h6><strong>Anyway, I wanna really thank you for all your support and appreciation!</strong></h6>
										    		    <h6><strong>Michelem</strong></h6>
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
										    <div class="box-footer" style="clear:both">
										    	<button type="submit" class="btn btn-primary" name="save" value="1">Save</button>
										    </div>
										</div>
	                            
									</section><!-- End left section -->
									
									<section class="right-section col-xs-12 col-md-6">			
															
			                            <!-- Dashboard box -->
										<div class="box box-primary">
											<div class="box-header">
												<!-- tools box -->
			                                    <div class="pull-right box-tools">
			                                        <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
			                                    </div><!-- /. tools -->
			                                    <i class="fa fa-dashboard"></i>
			                                    
			                                    <h3 class="box-title">Dashboard Settings</h3>
			                                </div>
											
			                                <div class="box-body">
												<!-- Temperatures scale F°/C°-->
												<div class="form-group">
													<label>Temperature units</label>
													<p>Select your preferred units to display temperature.</p>
													<div class="radio">
														<label>
															<input type="radio" name="dashboard_temp" value="c" <?php if ($dashboardTemp == "c") : ?>checked=""<?php endif; ?> />
															Celsius (C°)
														</label>                                                
													</div>
													<div class="radio">
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
														<input type="text" name="dashboard_refresh_time" id="option-dashboard-refresh-time" class="refresh-time" value="" />
													</div>
													<small>time in seconds, min 5 secs</small>
												</div>
			                                </div>
											<div class="box-footer">
												<button type="submit" class="btn btn-primary" name="save" value="1">Save</button>
											</div>
			                            </div>
		                            
									</section><!-- End right section -->

								</div><!-- End row -->
									                            	                          
								<!-- Pools box -->
	                            <div class="box box-primary">
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
										<div class="form-group">
	                                        <div class="row">
												<div class="col-xs-6">
													<strong>Pool URL</strong>
												</div>
												<div class="col-xs-3">
													<strong>Pool Username</strong>
												</div>
												<div class="col-xs-3">
													<strong>Pool Password</strong>
												</div>
	                                        </div>
										</div>
										<!-- Main Pool -->
										<div class="poolSortable ui-sortable">
											<?php $savedPools = json_decode($minerdPools);?>
											<?php $s = (count($savedPools) == 0) ? 3 : count($savedPools); ?>
											<?php for ($i=0;$i<=$s;$i++) : ?>
												<?php if ( isset($savedPools[$i]->url) && 
															$savedPools[$i]->url == $this->config->item('minera_pool_url') && 
															isset($savedPools[$i]->username) && 
															$savedPools[$i]->username == $this->config->item('minera_pool_username') && 
															isset($savedPools[$i]->password) && 
															$savedPools[$i]->password == $this->config->item('minera_pool_password') ) : ?>
												<!-- row pool for Minera -->
												<div class="form-group">
												    <div class="row sort-attach">
												    	<div class="col-xs-5">
												    		<div class="input-group">
												    			<span class="input-group-addon"><i class="fa fa-gift"></i></span>
												    			<input type="text" class="form-control" name="pool_url[]" data-ismain="0" value="stratum+tcp://multi.ghash.io:3333" readonly />
												    		</div>
												    	</div>
												    	<div class="col-xs-3">
												    		<div class="input-group">
												    			<span class="input-group-addon"><i class="fa fa-user"></i></span>
												    			<input type="text" class="form-control" name="pool_username[]" data-ismain="0" value="michelem.minera" readonly />
												    		</div>
												    	</div>
												    	<div class="col-xs-3">
												    		<div class="input-group">
												    			<span class="input-group-addon"><i class="fa fa-lock"></i></span>
												    			<input type="text" class="form-control" name="pool_password[]" data-ismain="0" value="x" readonly />
												    		</div>
												    	</div>
												    	<div class="col-xs-1">
												    		<button style="margin-top:5px;" class="btn btn-primary btn-xs help-pool-row" name="help-row" value="1"><i class="fa fa-question"></i></button>
												    	</div>
												    </div>
												    <div class="row minera-pool-help" style="display:none;">
												    	<div class="col-xs-11" style="margin-top:10px">
													    	<div class="callout callout-info">
																<h6><strong>Why can't I remove this pool?</strong></h6>
																<p><small>As you know, <a href="https://github.com/michelem09/minera" target="_blank">Minera is free and Open Source</a> and its author put much efforts and his free time on this. So to support its development you can't remove anymore the Minera's donation pool. But don't panic! This won't change anything, you can still move it down as latest failover along with how many pools you want, so you can be sure you won't give Minera even a cent, otherwise you can move it up and make me happy. Anyway thanks for your support.</small></p>
															</div>
												    	</div>
												    	<div class="col-xs-1">&nbsp;</div>
												    </div>
												</div>
												<?php else : ?>
												<div class="form-group pool-group">
												    <div class="row sort-attach pool-row">
												    	<div class="col-xs-5">
												    		<div class="input-group">
												    			<span class="input-group-addon"><i class="fa fa-cloud-<?php echo ($i == 0) ? "upload" : "download"; ?>"></i></span>
												    			<input type="text" class="form-control pool_url" placeholder="<?php echo ($i == 0) ? "Main" : "Failover"; ?> url" name="pool_url[]" data-ismain="<?php echo ($i == 0) ? "1" : "0"; ?>" value="<?php echo (isset($savedPools[$i]->url)) ? $savedPools[$i]->url : ''; ?>" />
												    		</div>
												    	</div>
												    	<div class="col-xs-3">
												    		<div class="input-group">
												    			<span class="input-group-addon"><i class="fa fa-user"></i></span>
												    			<input type="text" class="form-control pool_username" placeholder="username" name="pool_username[]" data-ismain="<?php echo ($i == 0) ? "1" : "0"; ?>" value="<?php echo (isset($savedPools[$i]->username)) ? $savedPools[$i]->username : ''; ?>"  />
												    		</div>
												    	</div>
												    	<div class="col-xs-3">
												    		<div class="input-group">
												    			<span class="input-group-addon"><i class="fa fa-lock"></i></span>
												    			<input type="text" class="form-control pool_password" placeholder="password" name="pool_password[]" data-ismain="<?php echo ($i == 0) ? "1" : "0"; ?>" value="<?php echo (isset($savedPools[$i]->password)) ? $savedPools[$i]->password : ''; ?>"  />
												    		</div>
												    	</div>
												    	<div class="col-xs-1">
												    		<button style="margin-top:5px;" class="btn btn-danger btn-xs del-pool-row" name="del-row" value="1"><i class="fa fa-times"></i></button>
												    	</div>
												    </div>
												</div>
												<?php endif; ?>
											<?php endfor; ?>
											<!-- fake row to be cloned -->
											<div class="form-group pool-group pool-group-master" style="display:none;">
											    <div class="row sort-attach pool-row">
											    	<div class="col-xs-5">
											    		<div class="input-group">
											    			<span class="input-group-addon"><i class="fa fa-cloud-download"></i></span>
											    			<input type="text" class="form-control pool_url" placeholder="Failover url" name="pool_url[]" data-ismain="0" value="" />
											    		</div>
											    	</div>
											    	<div class="col-xs-3">
											    		<div class="input-group">
											    			<span class="input-group-addon"><i class="fa fa-user"></i></span>
											    			<input type="text" class="form-control pool_username" placeholder="username" name="pool_username[]" data-ismain="0" value=""  />
											    		</div>
											    	</div>
											    	<div class="col-xs-3">
											    		<div class="input-group">
											    			<span class="input-group-addon"><i class="fa fa-lock"></i></span>
											    			<input type="text" class="form-control pool_password" placeholder="password" name="pool_password[]" data-ismain="0" value=""  />
											    		</div>
											    	</div>
											    	<div class="col-xs-1">
											    		<button style="margin-top:5px;" class="btn btn-danger btn-xs del-pool-row" name="del-row" value="1"><i class="fa fa-times"></i></button>
											    	</div>
											    </div>
											</div>
											
										</div><!-- sortable -->
										<div>
											<button class="btn btn-default btn-sm add-pool-row" name="add-row" value="1"><i class="fa fa-plus"></i> Add row</button>
										</div>
	                                </div>
									<div class="box-footer">
										<button type="submit" class="btn btn-primary" name="save" value="1">Save</button> <button type="submit" class="btn btn-danger" name="save_restart" value="1">Save & Restart Miner</button>
									</div>
	                            </div>
	                            
								<!-- Miner box -->
	                            <div class="box box-primary">
									<div class="box-header">
										<!-- tools box -->
	                                    <div class="pull-right box-tools">
	                                        <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
	                                    </div><!-- /. tools -->
	                                    <i class="fa fa-gear"></i>
	                                    
	                                    <h3 class="box-title">Miner Settings</h3>
	                                </div>
	
		                                <div class="box-body">
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
											
											<hr />
											
											<div class="guided-options">
												<!-- Auto-Detect -->
												<div class="form-group">
													<div class="checkbox">
														<label>
															<input type="checkbox" name="minerd_autodetect" value="1" <?php if ($minerdAutodetect) : ?>checked=""<?php endif; ?> />
															Enable device auto detection <small>(--gc3355-detect)</small>
														</label>                                                
													</div>
												</div>
												
												<!-- Auto-Tune -->
												<div class="form-group">
													<div class="checkbox">
														<label>
															<input type="checkbox" name="minerd_autotune" value="1" <?php if ($minerdAutotune) : ?>checked=""<?php endif; ?> />
															Enable frequency auto tuning <small>(--gc3355-autotune)</small>
														</label>                                                
													</div>
												</div>
												
												<!-- Logging -->
												<div class="form-group">
													<div class="checkbox">
														<label>
															<input type="checkbox" name="minerd_log" value="1" <?php if ($minerdLog) : ?>checked=""<?php endif; ?> />
															Enable logging <small>(--log)</small>
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
												
												<!-- Start Frequency -->												
												<div class="form-group">
													<label>Select starting frequency</label>
													<div class="margin-bottom" style="width:50%">
														<input type="text" name="minerd_startfreq" id="option-startfreq" value="" />
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
		                                            <h6>Write here any other option you want to include please refer to the <a href="https://github.com/siklon/cpuminer-gc3355">Github page</a> for the complete options list.</h6>
		                                        </div>	                                        
		                                        
											</div>
	                                        
	                                        <!-- Minerd manual options config -->
	                                        <div class="form-group manual-options">
	                                            <label>Manual options</label>
	                                            <p>You have chosen to add all options manually, I will only add for you the pools list, you have to take care of the rest.</p>
	                                            <textarea name="minerd_manual_settings" class="form-control" rows="5" placeholder="Example: --gc3355-detect --gc3355-autotune --freq=850 --retries=1" class="minerd_manual_settings"><?php echo $minerdManualSettings ?></textarea>
												<h6>Please do not include the command name or the pools (they are automatically added).</h6>
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
												<div class="input-group">
													<span class="input-group-addon"><i class="glyphicon glyphicon-hdd"></i></span>
													<input type="text" class="form-control" placeholder="Devices" name="minerd_autorestart_devices" value="<?php echo $minerdAutorestartDevices ?>" style="width:90px"/>
												</div>
												<h6>Check based on last share time (10 minutes without any share triggers the restart)</h6>
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
											
											<hr />
											
	                                        <!-- Minerd final config -->
	                                        <h3>Check your miner settings</h3>
											<div class="callout callout-info">
												<h4>Miner will start with this syntax:</h4>
												<h5><i><?php echo $this->config->item("screen_command") ?> <?php echo $this->config->item("minerd_command")."</i> <strong>".$minerdSettings ?></strong></h5>
												<h4>JSON Conf:</h4>
												<pre style="font-size:10px;"><?php $jsonConf =  json_decode($minerdJsonSettings); echo json_encode($jsonConf, JSON_PRETTY_PRINT); ?></pre>
											</div>

											<?php if ($savedFrequencies) : ?>
												<h3>Saved frequencies</h3>
												<div class="callout callout-light">
													<h6>Here is the string you can add to the extra options, but remember to uncheck the autotune option:</h6>
													<pre  id="miner-freq" style="font-size:10px">--gc3355-freq=<?php echo $savedFrequencies ?></pre>
												</div>
											<?php endif; ?>
												
	                                </div>
									<div class="box-footer">
										<button type="submit" class="btn btn-primary" name="save" value="1">Save</button> <button type="submit" class="btn btn-danger" name="save_restart" value="1">Save & Restart Miner</button>
									</div>
	                            </div>

								<!-- Topbar box -->
								<div class="box box-primary">
								    <div class="box-header">
								    	<!-- tools box -->
			                            <div class="pull-right box-tools">
			                                <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
			                            </div><!-- /. tools -->
			                            <i class="fa fa-dashboard"></i>
			                            
			                            <h3 class="box-title">Top bar Settings</h3>
			                        </div>
								    
			                        <div class="box-body">
								    	<p>Setup the top bar options</p>
								    		
							    		<!-- Altcoins rates -->
							    		<div class="form-group">
							    			<label>Altcoins rates</label>
							    			<?php $altdata = json_decode($cryptsy_data); ?>
							    			<?php if (is_object($altdata) && is_array($dashboard_coin_rates)) : ?>
							    				<p><small>Currently selected: </small><?php foreach ($dashboard_coin_rates as $altcoin) : ?><small class="badge bg-blue"><?php echo $altdata->$altcoin->codes ?></small>&nbsp;<?php endforeach; ?></p>
		
							    				<div class="input-group">
							    					<div class="input-group-addon">
							    						<i class="fa fa-btc"></i>
							    					</div>
							    					<select multiple class="form-control dashboard-coin-rates" name="dashboard_coin_rates[]" style="width:50%" size="10">
							    					<?php foreach ($altdata as $id => $values) : ?>
							    						<option value="<?php echo $id ?>" <?php echo (in_array($id, $dashboard_coin_rates)) ? "selected" : ""; ?>><?php echo $values->names . " - " . $values->codes ?></option>
							    					<?php endforeach; ?>
							    					</select>
							    				</div><!-- /.input group -->
							    				<small>Select max 5 rates to be displayed on the top bar</small>
											<?php else: ?>
							    				<p><small class="badge bg-red">There was a problem with the altcoins. Try refreshing the page.</small></p>
											<?php endif; ?>
										</div>
			                        </div>
								    <div class="box-footer">
								    	<button type="submit" class="btn btn-primary" name="save" value="1">Save</button>
								    </div>
			                    </div>
	                            
	                            <!-- System box -->
								<div class="box box-primary">
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

											<!-- timezone -->
	                                        <div class="form-group">
	                                            <label>System timezone</label>
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
	                                            <textarea name="system_extracommands" class="form-control" rows="5" placeholder="There isn't any error control here" class="system_extracommands"><?php echo $systemExtracommands ?></textarea>
												<h6>(WARNING: you could harm your controller putting wrong strings here.)</h6>
											</div>
												
	                                </div>
									<div class="box-footer">
										<button type="submit" class="btn btn-primary" name="save" value="1">Save</button>
									</div>
	                            </div>
	                            
								<!-- Mobileminer box -->
								<div class="box box-primary">
									<div class="box-header">
										<!-- tools box -->
	                                    <div class="pull-right box-tools">
	                                        <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
	                                    </div><!-- /. tools -->
	                                    <i class="fa fa-mobile-phone"></i>
	                                    
	                                    <h3 class="box-title">Mobileminer Settings</h3>
	                                </div>
									
	                                <div class="box-body">
										<p>If you cannot (or don't want) to completely expose to internet your Minera system you can choose to connect it to the awesome <a href="http://www.mobileminerapp.com/" target="_blank">Mobileminer app</a> to check your stats from everywhere you are.<br />Please follow the instruction on the <a href="http://www.mobileminerapp.com/#gettingStarted" target="_blank">Mobileminer website</a>. To get started you only need to signup with your email address to retrieve your application key.</p>
	
											<!-- mobileminer options -->
											<div class="form-group">
												<div class="checkbox">
													<label>
														<input type="checkbox" class="mobileminer-checkbox" name="mobileminer_enabled" value="1" <?php if ($mobileminerEnabled) : ?>checked=""<?php endif; ?> />
														Enable Mobileminer
													</label>                                                
												</div>
											</div>
											<div class="input-group">
												<label for="mobileminer_system_name">System Name</label>
												<input type="text" class="form-control" name="mobileminer_system_name" placeholder="Give a name to this Minera system to identify it" value="<?php echo $mobileminerSystemName ?>">
											</div>
											<div class="input-group">
												<label for="mobileminer_email">Email</label>
												<input type="text" class="form-control" name="mobileminer_email" placeholder="Email you used to signup Mobileminer" value="<?php echo $mobileminerEmail ?>">
											</div>
											<div class="input-group">
												<label for="mobileminer_appkey">Application Key</label>
												<input type="password" class="form-control" name="mobileminer_appkey" placeholder="Your Mobileminer Application Key" value="<?php echo $mobileminerAppkey ?>">
											</div>
	                                </div>
									<div class="box-footer">
										<button type="submit" class="btn btn-primary" name="save" value="1">Save</button>
									</div>
	                            </div>

							</form>

							<!-- User box -->
							<div class="box box-primary">
								<div class="box-header">
									<!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                                    </div><!-- /. tools -->
                                    <i class="fa fa-user"></i>
                                    
                                    <h3 class="box-title">User</h3>
                                </div>
								
								<form action="<?php site_url("app/dashboard") ?>" method="post" role="form">
									<input type="hidden" name="save_password" value="1" />
	                                <div class="box-body">
										<p>Change the Minera lock screen password</p>
										<div class="form-group">
                                        	<label for="password1">Password</label>
											<input type="password" class="form-control" name="password" placeholder="Password">
										</div>
										<div class="form-group">
                                        	<label for="password2">Repeat password</label>
											<input type="password" class="form-control" name="password2" placeholder="Repeat">
										</div>
	                                </div>
									<div class="box-footer">
										<button type="submit" class="btn btn-primary">Save password</button>
									</div>
								
								</form>
                            </div>
                        
                        </section><!-- /.left col -->
                        
					</div><!-- /.row -->

                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->
