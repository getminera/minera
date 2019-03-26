    <!-- Right side column. Contains the navbar and content of the page -->
    <aside class="right-side ">                	
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Node
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
                                        <p>You can change the Raspbian hostname where your RaspiNode is running</p>
                                        <div class="input-group">
	                                        <span class="input-group-addon"><i class="fa fa-tag"></i></span>
	                                        <input type="text" name="system_hostname" class="form-control" placeholder="Use numbers/letters, symbols allowed are dash and underscore" />
                                        </div>
									</div>
									
									<!-- system password -->
                                    <div class="form-group">
                                        <label>System password</label>
                                        <p>RaspiNode works with the system user <span class="badge bg-blue">pirate</span>, here you can change the system user password</p>
                                        <div class="input-group">
											<span class="input-group-addon"><i class="fa fa-user-secret"></i></span>
											<input type="password" class="form-control" id="system_password" name="system_password" placeholder="Password for RaspiNode system user">
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
						    	<p>You can export a JSON file with all the settings from your current RaspiNode system. This file can be imported to reproduce the same settings in a new RaspiNode system with a click (this will export everything excluding: user password, charts and stats). You can also save a single miner config to be used in future<em>*</em> or shared with the Minera community<em>**</em>.</p>
						    	
								<div class="import-export-box margin-bottom">
									<span class="btn btn-success fileinput-button" data-toggle="tooltip" data-title="File must be a JSON export file from a RaspiNode system">
										<i class="glyphicon glyphicon-plus"></i>
										Import file...
										<input class="import-file" type="file" name="import_system_config">
									</span>
									<span class="btn btn-warning export-action" data-toggle="tooltip" data-title="This generates a JSON file to be imported into RaspiNode">
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
								<h6><em>**</em> Sharing the miner config to the RaspiNode community won't share your pools settings</h6>
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
								<p>Change the RaspiNode lock screen password</p>
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
	                            	<button type="submit" class="btn btn-warning reset-action" data-reset-action="logs"><i class="fa fa-eraser"></i> Clear the RaspiNode logs</button>
									<h6>This will delete everything inside application/logs. This includes all RaspiNode application logs and also all the Miner logs.</h6>
								</div>
								<div class="form-group">
	                            	<button type="submit" class="btn btn-danger reset-factory-action"><i class="fa fa-recycle"></i> Reset to factory default</button>
									<h6>This will reset your RaspiNode to the factory default settings (it doesn't change anything at system level, only the web interface with all the relative data will be reset, this includes: lock password, stats, charts, miner settings, saved miner configs, pools, etc...)</h6>
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
