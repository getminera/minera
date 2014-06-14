            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side ">                
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Mining
                        <small>Dashboard</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="<?php echo site_url("app/settings") ?>"><i class="fa fa-gear"></i> Settings</a></li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">

					<div class="row">
                        
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
	                        <div class="row">
	                        
	                        	<!-- total hashrate widget -->
								<div class="col-lg-4 col-xs-4">
									<!-- small box -->
									<div class="small-box bg-red">
										<div class="inner">
											<h3 class="widget-total-hashrate"><i class="ion ion-loading-c"></i></h3>
											<p>Pool Hashrate</p>
										</div>
										<div class="icon"><i class="ion ion-ios7-speedometer-outline"></i></div>
										<a href="#hashrate-history" class="small-box-footer">History <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>
								
								<!-- hw/re widget -->
								<div class="col-lg-4 col-xs-4">
									<!-- small box -->
									<div class="small-box bg-light">
										<div class="inner">
											<h3 class="widget-hwre-rates"><i class="ion ion-loading-c"></i></h3>
											<p>Error/Rejected rates</p>
										</div>
										<div class="icon"><i class="ion ion-alert-circled"></i></div>
										<a href="#error-history" class="small-box-footer">Details <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>
								
								<!-- last share widget -->
								<div class="col-lg-4 col-xs-4">
									<!-- small box -->
									<div class="small-box bg-light-blue">
										<div class="inner">
											<h3 class="widget-last-share"><i class="ion ion-loading-c"></i></h3>
											<p>Last Share</p>
										</div>
										<div class="icon"><i class="ion ion-ios7-stopwatch-outline"></i></div>
										<a href="#miner-details" class="small-box-footer">Miner details <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>
							</div>							
							
							<div class="row">
							
								<!-- sys temp widget -->
								<div class="col-lg-4 col-xs-4">
									<!-- small box -->
									<div class="small-box sys-temp-box bg-blue">
										<div class="inner">
											<h3 class="widget-sys-temp"><i class="ion ion-loading-c"></i></h3>
											<p>System temperature</p>
										</div>
										<div class="icon"><i class="ion ion-thermometer"></i></div>
										<a href="#sysload" class="small-box-footer sys-temp-footer">...<i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>
								
								<!-- main pool -->
								<div class="col-lg-4 col-xs-4">
									<!-- small box -->
									<div class="small-box bg-dark">
										<div class="inner">
											<h3 class="widget-main-pool"><i class="ion ion-loading-c"></i></h3>
											<p>Checking...</p>
										</div>
										<div class="icon"><i class="ion ion-ios7-cloud-upload-outline"></i></div>
										<a href="#pools-details" class="small-box-footer">Pools details <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>
								
								<!-- uptime widget -->
								<div class="col-lg-4 col-xs-4">
									<!-- small box -->
									<div class="small-box bg-aqua">
										<div class="inner">
											<h3 class="widget-uptime"><i class="ion ion-loading-c"></i></h3>
											<p>Miner uptime</p>
										</div>
										<div class="icon"><i class="ion ion-ios7-timer-outline"></i></div>
										<a href="#miner-details" class="small-box-footer uptime-footer">... <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>
								
							</div>
							
                        </section>
                        
                        <!-- Top section -->
                        <section class="col-md-12 connectedSortable ui-sortable top-section">
                        
                        	<!-- Miner box -->
                            <div class="box box-light">
                               	<div class="overlay"></div>
                               	<div class="loading-img"></div>
                                <div class="box-header" style="cursor: move;">
                                    <!-- tools box -->
                                    <div class="pull-right box-tools">
                                    	<small class="auto-refresh-time"></small>&nbsp;
                                    	<button class="btn btn-danger btn-xs refresh-btn" data-toggle="tooltip" title="" data-original-title="Refresh"><i class="fa fa-refresh"></i></button>
                                        <button class="btn btn-default btn-xs save-freq" data-toggle="tooltip" title="" data-original-title="Save current frequencies"><i class="fa fa-pencil"></i></button>
                                        <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                                    </div><!-- /. tools -->
                                    <i class="fa fa-desktop"></i>

                                    <h3 class="box-title" id="miner-details">Miner details</h3>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
		                                        <!-- .table - Uses sparkline charts-->
		                                        <table id="miner-table-details" class="table table-striped datatable">
		                                            <thead>
		                                            <tr>
		                                                <th>DEV</th>
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
                                <?php if ($savedFrequencies) : ?>
	                                <div class="box-footer">
	                                	<div class="legend pull-right">
	                                		<h6>Colors based on last share time: <i class="fa fa-circle text-success"></i> Good&nbsp;&nbsp;&nbsp;<i class="fa fa-circle text-warning"></i> Warning&nbsp;&nbsp;&nbsp;<i class="fa fa-circle text-danger"></i> Critical&nbsp;&nbsp;&nbsp;<i class="fa fa-circle text-muted"></i> Possibly dead</h6>
	                                	</div>
	                                	<button class="btn btn-primary btn-sm btn-saved-freq" data-toggle="tooltip" title="" data-original-title="Look at saved frequencies"><i class="fa fa-eye"></i> Saved frequencies</button>
	                                	<div class="freq-box" style="display:none; margin-top:10px;">
		                                	<h6>You can find this on the <a href="<?php echo site_url("app/settings") ?>">settings page</a> too.</h6>
											<pre id="miner-freq" style="font-size:10px; margin-top:10px;">--gc3355-freq=<?php echo $savedFrequencies ?></pre>
	                                	</div>
									</div>
                                <?php endif; ?>
                            </div><!-- /.miner box -->
                            
							<!-- Pools box -->
                            <div class="box box-light">
                               	<div class="overlay"></div>
                               	<div class="loading-img"></div>
                                <div class="box-header" style="cursor: move;">
                                    <!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                                    </div><!-- /. tools -->
                                    <i class="fa fa-cloud"></i>

                                    <h3 class="box-title" id="pools-details">Pools details</h3>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
		                                        <!-- .table - Uses sparkline charts-->
		                                        <table id="pools-table-details" class="table table-striped datatable">
		                                            <thead>
		                                            <tr>
		                                                <th>Pool</th>
		                                                <th>Url</th>
		                                                <th>Priority</th>
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
												<p class="pool-alert"></p>
		                                    </div>
                                        </div>
                                    </div><!-- /.row - inside box -->
                                </div><!-- /.box-body -->
                                <div class="box-footer">
                                	<h6>Legend: <strong>CS</strong> = Current Shares, <strong>PS</strong> = Previous shares, <strong>CA</strong> = Current Accepted, <strong>PA</strong> = Previous Accepted, <strong>CR</strong> = Current Rejected, <strong>PR</strong> = Previous Rejected</h6>
                                	<h6><strong>Current</strong> is the current or last session, <strong>Previous</strong> is the total of all previous sessions. Pool HashRate is based on shares over the time per session.</h6>
                                </div>
                            </div><!-- /.miner box -->  
                            
                        </section>
                        
                        <!-- Right col -->
                        <section class="col-md-6 connectedSortable ui-sortable right-section">
                        
                        	<!-- Tree box -->
                            <div class="box box-dark">
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
                                                 
                        </section><!-- Right col -->
                        
						<!-- Left col -->
                        <section class="col-md-6 connectedSortable ui-sortable left-section">
                            
                        	<!-- Hashrate box chart -->
							<div class="box box-primary">
                               	<div class="overlay"></div>
                               	<div class="loading-img"></div>
								<div class="box-header">
									<!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                                    </div><!-- /. tools -->
                                    <i class="fa fa-bar-chart-o"></i>
                                    
                                    <h3 class="box-title" id="hashrate-history">Hashrate History</h3>
                                </div>
                                <div class="box-body chart-responsive">
                                	<div class="chart" id="hashrate-chart" style="height:160px;"></div>
                                </div>
                            </div><!-- /.hashrate box -->
                            
							<div class="box box-primary">
                               	<div class="overlay"></div>
                               	<div class="loading-img"></div>
								<div class="box-header">
									<!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                                    </div><!-- /. tools -->
                                    <i class="fa fa-bullseye"></i>
                                    
                                    <h3 class="box-title" id="error-history">Accepted/Rejected/Errors</h3>
                                </div>
                                <div class="box-body chart-responsive">
                                	<div class="chart" id="rehw-chart" style="height:160px;"></div>
                                </div>
                            </div>
                            
							<!-- System box -->
                            <div class="box box-light">
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
                        
                        </section><!-- /.left col -->
                       
					</div><!-- /.row -->
					
					<div class="row">
					
                        <!-- Bottom section -->
						<section class="col-md-12 connectedSortable ui-sortable bottom-section">
                        
							<!-- Real time log box -->
                            <div class="box box-light">
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
                            
                            <!-- Donations box -->
                            <div class="box bg-light box-danger">
                                <div class="box-header">
									<!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                                    </div><!-- /. tools -->
                                    <i class="fa fa-gift"></i>

                                    <h3 class="box-title">Donations</h3>
                                </div>
                                <div class="box-body">
                                    <p class="more-line-height">If you like Minera, please consider a donation to support it. <strong>Bitcoin</strong>: <code><a href="bitcoin:1AmREReHNLec9EaW7gLRdW31LNSDA9SGR1" target="_blank">1AmREReHNLec9EaW7gLRdW31LNSDA9SGR1</a></code> <strong>Litecoin</strong>: <code><a href="litecoin:LLPmAT9gDwmiSdqwWEZu6mpUDmrNAnYBdC" target="_blank">LLPmAT9gDwmiSdqwWEZu6mpUDmrNAnYBdC</a></code> <strong>Dogecoin</strong>: <code><a href="dogecoin:DLAHwNxfUTUcePewbkvwvAouny19mcosA7" target="_blank">DLAHwNxfUTUcePewbkvwvAouny19mcosA7</a></code></p>
                                </div><!-- /.box-body -->
                            </div>
                            
						</section>
					</div>

                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->