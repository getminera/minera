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
                        
                        <!-- Top section -->
                        <section class="col-md-12 connectedSortable ui-sortable">
								
							<form action="<?php site_url("app/dashboard") ?>" method="post" role="form">
								<input type="hidden" name="save_settings" value="1" />                                                    
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
											<p>Please input here your command options, Minera will start the <code>minerd</code> command within a screen session.</p>
	
	                                        <!-- Minerd options config -->
	                                        <div class="form-group">
	                                            <label>Minerd options</label>
	                                            <textarea name="minerd_settings" class="form-control" rows="5" placeholder="Example: --gc3355-detect --gc3355-autotune --freq=850 -o stratum+tcp://multi.ghash.io:3333 -u michelem.minera -p x --retries=1"><?php echo $this->redis->get('minerd_settings') ?></textarea>
												<h6>Please do not include the command name!</h6>
												<div class="callout callout-info">
													<h4>Miner will start with this command line:</h4>
													<h5><i><?php echo $this->config->item("screen_command") ?> <?php echo $this->config->item("minerd_command")."</i> <strong>".$this->redis->get('minerd_settings') ?></strong></h5>
												</div>
	                                        </div>
												                                        
											<!-- Auto-recover -->
											<div class="form-group">
												<div class="checkbox">
													<label>
														<input type="checkbox" name="minerd_autorecover" value="1" <?php if ($minerd_autorecover) : ?>checked=""<?php endif; ?> />
														Enable auto-recover mode <small>(If minerd process dies Minera restarts it)</small>
													</label>                                                
												</div>
											</div>
												
	                                </div>
									<div class="box-footer">
										<button type="submit" class="btn btn-primary">Save settings</button>
									</div>
	                            </div>
	                            
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
										<button type="submit" class="btn btn-primary">Save settings</button>
									</div>
	                            </div>

							</form>                            

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