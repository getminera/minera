			<!-- Right side column. Contains the navbar and content of the page -->
			<aside class="right-side ">				   
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
						Mining
						<small>Charts</small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="<?php echo site_url("app/dashboard") ?>"><i class="fa fa-dashboard"></i> Dashboard</a></li>
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
						
						<!-- Charts section Right -->
						<section class="col-md-6 col-xs-12 connectedSortable ui-sortable right-section">
							
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
									<h4>Hourly</h4>
									<div class="chart" id="hashrate-chart-hourly" style="height:160px;"></div>
								</div>
								<div class="box-body chart-responsive">
									<h4>Daily</h4>
									<div class="chart" id="hashrate-chart-daily" style="height:160px;"></div>
								</div>
								<div class="box-body chart-responsive">
									<h4>Monthly</h4>
									<div class="chart" id="hashrate-chart-monthly" style="height:160px;"></div>
								</div>
								<div class="box-body chart-responsive">
									<h4>Yearly</h4>
									<div class="chart" id="hashrate-chart-yearly" style="height:160px;"></div>
								</div>
							</div><!-- /.hashrate box -->
						
						</section><!-- /.right col -->
					   
						<!-- Charts section Left -->
						<section class="col-md-6 col-xs-12 connectedSortable ui-sortable left-section">
							
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
									<h4>Hourly</h4>
									<div class="chart" id="rehw-chart-hourly" style="height:160px;"></div>
								</div>
								<div class="box-body chart-responsive">
									<h4>Daily</h4>
									<div class="chart" id="rehw-chart-daily" style="height:160px;"></div>
								</div>
								<div class="box-body chart-responsive">
									<h4>Monthly</h4>
									<div class="chart" id="rehw-chart-monthly" style="height:160px;"></div>
								</div>
								<div class="box-body chart-responsive">
									<h4>Yearly</h4>
									<div class="chart" id="rehw-chart-yearly" style="height:160px;"></div>
								</div>
							</div>
						
						</section><!-- /.left col -->
						
					</div><!-- /.row -->

				</section><!-- /.content -->
			</aside><!-- /.right-side -->
		</div><!-- ./wrapper -->