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
										<?php for ($i=0;$i<=3;$i++) : ?>
										<div class="form-group pool-group">
										    <div class="row sort-attach">
										    	<div class="col-xs-6">
										    		<div class="input-group">
										    			<span class="input-group-addon"><i class="fa fa-cloud-<?php echo ($i == 0) ? "upload" : "download"; ?>"></i></span>
										    			<input type="text" class="form-control pool_url" placeholder="<?php echo ($i == 0) ? "Main" : "Failover"; ?> url" name="pool_url[<?php echo $i ?>]" value="<?php echo (isset($savedPools[$i]->url)) ? $savedPools[$i]->url : ''; ?>" />
										    		</div>
										    	</div>
										    	<div class="col-xs-3">
										    		<div class="input-group">
										    			<span class="input-group-addon"><i class="fa fa-user"></i></span>
										    			<input type="text" class="form-control pool_username" placeholder="username" name="pool_username[<?php echo $i ?>]" value="<?php echo (isset($savedPools[$i]->username)) ? $savedPools[$i]->username : ''; ?>"  />
										    		</div>
										    	</div>
										    	<div class="col-xs-3">
										    		<div class="input-group">
										    			<span class="input-group-addon"><i class="fa fa-lock"></i></span>
										    			<input type="text" class="form-control pool_password" placeholder="password" name="pool_password[<?php echo $i ?>]" value="<?php echo (isset($savedPools[$i]->password)) ? $savedPools[$i]->password : ''; ?>"  />
										    		</div>
										    	</div>
										    </div>
										</div>
										<?php endfor; ?>
										</div><!-- sortable -->											
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
											
											<!-- Auto-recover -->
											<div class="form-group">
												<div class="checkbox">
													<label>
														<input type="checkbox" name="minerd_autorecover" value="1" <?php if ($minerdAutorecover) : ?>checked=""<?php endif; ?> />
														Enable auto-recover mode <small>(If minerd process dies Minera restarts it)</small>
													</label>                                                
												</div>
											</div>
											
											<!-- Minerd delay time option -->
	                                        <div class="form-group">
	                                            <label>Autostart Delay Time</label>
												<div class="input-group">
													<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
													<input type="text" class="form-control" placeholder="Delay time" name="minerd_delaytime" value="<?php echo $minerdDelaytime ?>" />
												</div>
	                                            <h6>With this option you can set a delay in rc.local before starting minerd to give the system the time to detect devices.</h6>
	                                        </div>	
											
											
											<hr />
											
	                                        <!-- Minerd final config -->
	                                        <h3>Check your miner settings</h3>
											<div class="callout callout-info">
												<h4>Miner will start with this command line:</h4>
												<h5><i><?php echo $this->config->item("screen_command") ?> <?php echo $this->config->item("minerd_command")."</i> <strong>".$minerdSettings ?></strong></h5>
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
												<div class="row">
													<div class="col-xs-3">
														<div class="input-group">
															<div class="input-group-addon">
																<i class="fa fa-clock-o"></i>
															</div>
															<input type="text" class="form-control" name="dashboard_refresh_time" placeholder="seconds" value="<?php echo $dashboard_refresh_time ?>" />
														</div><!-- /.input group -->
														<small>time in seconds, min 5 secs</small>
													</div>
													<div class="col-xs-9"></div>
												</div>
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