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
																<p><small>As you know, <a href="https://github.com/michelem09/minera" target="_blank">Minera is free and Open Source</a> and its author put much efforts and his free time on this. So to support its development you can't remove anymore the Minera's donation pool. But don't panic! This won't change anything, you can still move it down as latest failover along with how many pools you want, so you can be sure you won't give Minera any cent, otherwise you can move it up and make me happy. Anyway thanks for your support.</small></p>
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
													<div class="row">
														<div class="col-xs-4">
															<div class="input-group">
																<select class="form-control" name="minerd_startfreq">
																	<option value="0">default</option>
																<?php $inc = 15; ?>
																<?php for ($s=600; $s<=1400; $s++) : ?>
																	<option value="<?php echo $s ?>" <?php echo ($minerdStartfreq == $s) ? "selected" : ""; ?>><?php echo $s ?>MHz</option>
																<?php endfor; ?>
																</select>
															</div><!-- /.input group -->
														</div>
														<div class="col-xs-8"></div>
													</div>
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
												<h4>Miner will start with this command line:</h4>
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
										<p>Setup the dashboard options</p>
	
											<!-- Refresh time -->
											<div class="form-group">
												<label>Refresh time</label>
												<div class="input-group">
													<div class="input-group-addon">
														<i class="fa fa-clock-o"></i>
													</div>
													<input type="text" class="form-control" name="dashboard_refresh_time" placeholder="seconds" value="<?php echo $dashboard_refresh_time ?>" style="width:90px" />
												</div><!-- /.input group -->
												<small>time in seconds, min 5 secs</small>
											</div>
											
											<!-- Altcoins rates -->
											<div class="form-group">
												<label>Top bar Altcoins</label>
												<?php $altdata = json_decode($cryptsy_data); $altcoins = json_decode($dashboard_coin_rates); if (is_array($altcoins)) : ?>
													<p><small>Currently selected: </small><?php foreach ($altcoins as $altcoin) : ?><small class="badge bg-blue"><?php echo $altdata->$altcoin->codes ?></small>&nbsp;<?php endforeach; ?></p>
												<?php endif; ?>
												<div class="input-group">
													<div class="input-group-addon">
														<i class="fa fa-btc"></i>
													</div>
													<select multiple class="form-control dashboard-coin-rates" name="dashboard_coin_rates[]" style="width:50%" size="10">
													<?php foreach ($altdata as $id => $values) : ?>
														<option value="<?php echo $id ?>" <?php echo (in_array($id, json_decode($dashboard_coin_rates))) ? "selected" : ""; ?>><?php echo $values->names . " - " . $values->codes ?></option>
													<?php endforeach; ?>
													</select>
												</div><!-- /.input group -->
												<small>Select max 3 rates to be displayed on the top bar</small>
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
	
											<!-- rc.local extra commands -->
	                                        <div class="form-group">
	                                            <label>On boot extra commands (rc.local)</label>
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