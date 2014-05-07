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
	                        <section class="col-md-12">
    	                    	<div class="alert alert-<?php echo $message_type ?> alert-dismissable">
									<i class="fa fa-check"></i>
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<?php echo $message ?>.
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

                                    <h3 class="box-title">Miner details</h3>
                                </div>
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
		                                        <!-- .table - Uses sparkline charts-->
		                                        <table class="table table-striped">
		                                            <thead>
		                                            <tr>
		                                                <th>DEV</th>
		                                                <th>Frequency</th>
		                                                <th>Hashrate</th>
		                                                <th>Shares</th>
		                                                <th>Accepted</th>
		                                                <th>Rejected</th>
		                                                <th>Errors</th>
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
                                    
                                    <h3 class="box-title">Hashrate History</h3>
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
                                    
                                    <h3 class="box-title">Rejected/Errors</h3>
                                </div>
                                <div class="box-body chart-responsive">
                                	<div class="chart" id="rehw-chart" style="height:160px;"></div>
                                </div>
                            </div>
                        
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