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
                                                    
                            <div class="box box-primary">
								<div class="box-header">
									<!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                                    </div><!-- /. tools -->
                                    <i class="fa fa-gear"></i>
                                    
                                    <h3 class="box-title">Settings</h3>
                                </div>
								
								<form action="<?php site_url("app/dashboard") ?>" method="post" role="form">
									<input type="hidden" name="save_settings" value="1" />
	                                <div class="box-body">
										<p>Please input here your command options, Minera will start the <code>minerd</code> command within a screen session.</p>
	
	                                        <!-- textarea -->
	                                        <div class="form-group">
	                                            <label>Minerd options</label>
	                                            <textarea name="minerd_settings" class="form-control" rows="5" placeholder="Example: --gc3355=/dev/ttyACM0,/dev/ttyACM1,/dev/ttyACM2 --gc3355-autotune --freq=850 --url=stratum+tcp://multi.ghash.io:3333 --userpass=michelem.1:x --retries=1"><?php echo $this->redis->get('minerd_settings') ?></textarea>
												<h6>Please do not include the command name!</h6>
												<div class="callout callout-info">
													<h4>Miner will start with this command line:</h4>
													<h5><i><?php echo $this->config->item("screen_command") ?> <?php echo $this->config->item("minerd_command")."</i> <strong>".$this->redis->get('minerd_settings') ?></strong></h5>
												</div>
	                                        </div>
	
	                                </div>
									<div class="box-footer">
										<button type="submit" class="btn btn-primary">Save settings</button>
									</div>
								
								</form>
                            </div>
                            
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
										<p>Change the dashboard password</p>
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