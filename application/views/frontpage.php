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
                        
                        <section class="col-md-12 widgets-section">
	                        <div class="row">
								<div class="col-lg-4 col-xs-4">
									<!-- small box -->
									<div class="small-box bg-red">
										<div class="inner">
											<h3 class="widget-total-hashrate"><i class="ion ion-loading-c"></i></h3>
											<p>Total Hashrate</p>
										</div>
										<div class="icon"><i class="ion ion-ios7-speedometer-outline"></i></div>
										<a href="#hashrate-history" class="small-box-footer">History <i class="fa fa-arrow-circle-right"></i></a>
									</div>
								</div>
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
		                                                <th>Hashrate</th>
		                                                <th>Shares</th>
		                                                <th>Accepted</th>
		                                                <th>Rejected</th>
		                                                <th>Errors</th>
		                                                <th>Last share</th>
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
									<h6 class="miner-uptime"></h6>
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
		                                        <table class="table">
		                                            <thead>
		                                            <tr>
		                                                <th>Pool Url</th>
		                                                <th>Priority</th>
		                                                <th>Status</th>
		                                                <th>Username</th>
		                                                <th>Password</th>
		                                            </tr>
		                                            </thead>
		                                            <tbody class="pools_table">
													</tbody>
												</table><!-- /.table -->
		                                    </div>
                                        </div>
                                    </div><!-- /.row - inside box -->
                                </div><!-- /.box-body -->
                            </div><!-- /.miner box -->  
                            
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
                                    <i class="fa fa-warning"></i>
                                    
                                    <h3 class="box-title" id="error-history">Rejected/Errors</h3>
                                </div>
                                <div class="box-body chart-responsive">
                                	<div class="chart" id="rehw-chart" style="height:160px;"></div>
                                </div>
                            </div>
                            
							<!-- System box -->
                            <div class="box box-success">
                               	<div class="overlay"></div>
                               	<div class="loading-img"></div>
                                <div class="box-header" style="cursor: move;">
                                	<!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <button class="btn btn-default btn-xs" data-widget="collapse" data-toggle="tooltip" title="" data-original-title="Collapse"><i class="fa fa-minus"></i></button>
                                    </div><!-- /. tools -->
                                    <i class="fa fa-tasks"></i>

                                    <h3 class="box-title">System Load</h3>
                                </div><!-- /.box-header -->
                                <div class="box-body" style="display: block;">
                                    <div class="row padding-vert sysload" ></div>
                                </div><!-- /.box-body -->
                            </div><!-- /.system box -->
                        
                        </section><!-- /.left col -->
                       
					</div><!-- /.row -->
					
					<div class="row">
					
                        <!-- Bottom section -->
						<section class="col-md-12 connectedSortable ui-sortable bottom-section">
                            
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